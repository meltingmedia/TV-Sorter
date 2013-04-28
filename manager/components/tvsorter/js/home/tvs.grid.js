/**
 * Loads a grid of TVs assigned to the Template.
 *
 * @class MODx.grid.TemplateTV
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-template-tv
 */
MODx.grid.TemplateTV = function(config) {
    config = config || {};
    var tt = new Ext.ux.grid.CheckColumn({
        header: _('access')
        ,dataIndex: 'access'
        ,width: 70
        ,sortable: false
    });
    Ext.applyIf(config,{
        title: _('template_assignedtv_tab')
        ,id: 'modx-grid-template-tv'
        //,url: MODx.config.connectors_url+'element/template/tv.php'
        ,url: TVSorter.config.connector_url
        ,fields: ['id','name','description','tv_rank','access','category_name','category']
        ,baseParams: {
            action: 'tv/getList'
            ,template: config.template
        }
        ,saveParams: {
            template: config.template
        }
        ,paging: true
        ,plugins: tt
        ,remoteSort: true

        ,grouping: true
        ,groupBy: 'category_name'
        ,singleText: _('tv')
        ,pluralText: _('tvs')
        ,ddGroup: 'tvDragSort'
        ,enableDragDrop: true
        ,sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
            ,listeners: {
                beforerowselect: function(sm, idx, keep, record) {
                    sm.grid.ddText = '<div>'+ record.data.name +'</div>';
                }
            }
        })
        ,view: new Ext.grid.GroupingView({
            forceFit: true
            ,hideGroupedColumn: true
            ,enableGroupingMenu: false
            ,enableNoGroups: false
            ,scrollOffset: 0
            ,headersDisabled: true
            ,showGroupName: false
            ,groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "'
                +(config.pluralText || _('records')) + '" : "'
                +(config.singleText || _('record'))+'"]})'
        })
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,editor: { xtype: 'textfield' ,allowBlank: false }
            ,sortable: true
        },{
            header: _('category')
            ,dataIndex: 'category_name'
            ,width: 150
            ,sortable: true
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 350
            ,editor: { xtype: 'textfield' }
            ,sortable: false
        },tt,{
            header: _('rank')
            ,dataIndex: 'tv_rank'
            ,width: 100
            ,editor: { xtype: 'textfield' ,allowBlank: false }
            ,sortable: true
        }]
        ,tbar: ['->',{
            xtype: 'modx-combo-category'
            ,name: 'filter_category'
            ,hiddenName: 'filter_category'
            ,id: 'modx-temptv-filter-category'
            ,emptyText: _('filter_by_category')
            ,value: ''
            ,allowBlank: true
            ,width: 150
            ,listeners: {
                'select': {fn: this.filterByCategory, scope:this}
            }
        },'-',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-temptv-search'
            ,emptyText: _('search_ellipsis')
            ,listeners: {
                'change': {fn: this.search, scope: this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: this.blur
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,id: 'modx-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });

    if (config.grouping) {
        Ext.apply(config, {
            view: new Ext.grid.GroupingView({
                forceFit: true
                ,hideGroupedColumn: true
                ,enableGroupingMenu: false
                ,enableNoGroups: false
                ,scrollOffset: 0
                ,headersDisabled: true
                ,showGroupName: false
                ,groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "'
                    +(config.pluralText || _('records')) + '" : "'
                    +(config.singleText || _('record'))+'"]})'
            })
        });
    };

    MODx.grid.TemplateTV.superclass.constructor.call(this,config);

    this.on('afteredit', function(e) {
        if (e.field == 'access') {
            var store = e.grid.getStore();
            var total = store.queryBy(function(rec, id) {
                if (rec.data['category_name'] == e.record.data['category_name']) {
                    return true;
                }
                return false;
            });
            var actives = store.queryBy(function(rec, id) {
                if (rec.data.tv_rank != '-' && rec.data.category_name == e.record.data['category_name']) return true;
                return false;
            });
            var inactives = store.queryBy(function(rec, id) {
                if (rec.data.tv_rank == '-' && rec.data.category_name == e.record.data['category_name']) return true;
                return false;
            });
            var currentIdx = store.indexOf(e.record);

            if (e.value === true) {
                if (actives.length > 0) {
                    var last = actives.last();
                    var to = store.indexOf(last);
                } else if (total.length > 1) {
                    last = total.last();
                    to = store.indexOf(last);
                }

                e.record.set('tv_rank', actives.length);

                if (to && to != currentIdx) {
                    store.removeAt(currentIdx);
                    store.insert(to, e.record);
                }
            } else {
                if (inactives.length > 0) {
                    last = inactives.last();
                    to = store.indexOf(last) + 1;
                } else if (total.length > 0) {
                    var first = total.first();
                    to = store.indexOf(first);
                }
                var rank = e.record.get('tv_rank');
                e.record.set('tv_rank', '-');

                // Lower the tv_rank for impacted TVs
                var impacted = store.queryBy(function(rec, id) {
                    if (rec.data.tv_rank > rank && rec.data.category_name == e.record.data['category_name']) return true;
                    return false;
                });
                impacted.each(function(rec, idx, list) {
                    rec.set('tv_rank', (rec.get('tv_rank') - 1));
                });

                if (to && to != currentIdx) {
                    store.removeAt(currentIdx);
                    store.insert(to, e.record);
                }
            }
        }
    }, this);


    this.on('render', function(grid) {
        this.dropTarget = new Ext.dd.DropTarget(grid.getView().mainBody, {
            ddGroup: 'tvDragSort'
            ,copy: false
            ,notifyOver: function(dragSource, e, data) {
                if (dragSource.getDragData(e)) {
                    var targetNode = dragSource.getDragData(e).selections[0]
                        ,sourceNode = data.selections[0];

                    if ((sourceNode.data['category_name'] != targetNode.data['category_name']) || !sourceNode.data['access'] || !targetNode.data['access'] || (sourceNode.data['id'] == targetNode.data['id'])) {
                        return this.dropNotAllowed;
                    }

                    return this.dropAllowed;
                }

                return this.dropNotAllowed;
            }
            ,notifyDrop: function(dragSource, e, data) {
                var sm = grid.getSelectionModel()
                    ,rows = sm.getSelections();

                if (dragSource.getDragData(e)) {
                    var targetNode = dragSource.getDragData(e).selections[0]
                        ,sourceNode = data.selections[0];

                    if ((targetNode.id != sourceNode.id) && (targetNode.data['category_name'] === sourceNode.data['category_name'])) {
                        var from = sourceNode.get('tv_rank');
                        var to = targetNode.get('tv_rank');
                        var store = grid.getStore();
                        var sourceIdx = store.indexOf(sourceNode);
                        var targetIdx = store.indexOf(targetNode);

                        store.removeAt(sourceIdx);
                        store.insert(targetIdx, sourceNode);

                        var lower = store.queryBy(function(rec, id) {
                            if (rec.data.tv_rank > from &&
                                rec.data.tv_rank <= to &&
                                rec.data.category_name == sourceNode.data['category_name']
                                ) {
                                return true;
                            }
                            return false;
                        });
                        lower.each(function(rec, idx, list) {
                            rec.set('tv_rank', (rec.get('tv_rank') - 1));
                        });

                        var higher = store.queryBy(function(rec, id) {
                            if (rec.data.tv_rank < from &&
                                rec.data.tv_rank >= to &&
                                rec.data.tv_rank != '-' &&
                                rec.data.category_name == sourceNode.data['category_name']
                                ) {
                                return true;
                            }
                            return false;
                        });
                        higher.each(function(rec, idx, list) {
                            rec.set('tv_rank', (~~(rec.get('tv_rank')) + 1));
                        });

                        sourceNode.set('tv_rank', to);
                    }
                }
            }
        });
    }, this);
};
Ext.extend(MODx.grid.TemplateTV,MODx.grid.Grid,{

    filterByCategory: function(cb,rec,ri) {
        this.getStore().baseParams['category'] = cb.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.search = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        Ext.getCmp('modx-temptv-filter-category').setValue('');
        this.getBottomToolbar().changePage(1);
        this.refresh();
        return true;
    }
    ,clearFilter: function() {
        this.getStore().baseParams = {
            action: 'tv/getList'
        };
        Ext.getCmp('modx-temptv-filter-category').reset();
        Ext.getCmp('modx-temptv-search').setValue('');
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }

});
Ext.reg('modx-grid-template-tv',MODx.grid.TemplateTV);

// Override to add required parameters/objects to "fireEvent"
Ext.ns('Ext.ux.grid');
Ext.ux.grid.CheckColumn = function (a) {
    Ext.apply(this, a);
    if (!this.id) {
        this.id = Ext.id()
    }
    this.renderer = this.renderer.createDelegate(this)
};
Ext.ux.grid.CheckColumn.prototype = {
    init: function (b) {
        this.grid = b;
        this.grid.on('render', function () {
            var a = this.grid.getView();
            a.mainBody.on('mousedown', this.onMouseDown, this)
        }, this);
        this.grid.on('destroy', this.onDestroy, this)
    },
    onMouseDown: function (e, t) {
        this.grid.fireEvent('rowclick'
            ,this.grid
            ,this.grid.getView().findRowIndex(t)
            ,e
        );
        if (t.className && t.className.indexOf('x-grid3-cc-' + this.id) != -1) {
            e.stopEvent();
            var a = this.grid.getView().findRowIndex(t);
            var b = this.grid.store.getAt(a);
            b.set(this.dataIndex, !b.data[this.dataIndex]);
            this.grid.fireEvent('afteredit', {
                grid: this.grid
                ,record: b
                ,field: this.dataIndex
                ,value: b.data[this.dataIndex]
                ,originalValue: !b.data[this.dataIndex]
                ,row: a
                ,column: this.grid.getView().findCellIndex(t)
            })
        }
    },
    renderer: function (v, p, a) {
        p.css += ' x-grid3-check-col-td';
        return '<div class="x-grid3-check-col' + (v ? '-on' : '') + ' x-grid3-cc-' + this.id + '">&#160;</div>'
    },
    onDestroy: function () {
        var mainBody = this.grid.getView().mainBody;
        if (mainBody) {
            mainBody.un('mousedown', this.onMouseDown, this);
        }
    }
};
Ext.preg('checkcolumn', Ext.ux.grid.CheckColumn);
Ext.grid.CheckColumn = Ext.ux.grid.CheckColumn;
