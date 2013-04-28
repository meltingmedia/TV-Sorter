Ext.ns('TVSorter');
/**
 * @class TVSorter.TemplatesGrid
 * @extends MODx.grid.Grid
 * @param config
 * @xtype tvsorter-grid-templates
 */
TVSorter.TemplatesGrid = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url: TVSorter.config.connector_url
        ,baseParams: {
            action: 'template/getList'
        }
        ,fields: ['id', 'templatename', 'description', 'category_name', 'total_tvs']
        ,paging: true
        ,remoteSort: true

        ,grouping: true
        ,groupBy: 'category_name'
        ,singleText: _('template')
        ,pluralText: _('templates')

        ,columns: [{
            header: _('tvsorter.name')
            ,dataIndex: 'templatename'
        },{
            header: _('tvsorter.description')
            ,dataIndex: 'description'
        },{
            dataIndex: 'category_name'
        },{
            header: _('template_assignedtv_tab')
            ,dataIndex: 'total_tvs'
            ,fixed: true
            ,width: 250
            ,align: 'center'
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

    TVSorter.TemplatesGrid.superclass.constructor.call(this, config);
};

Ext.extend(TVSorter.TemplatesGrid, MODx.grid.Grid, {
    // Generates the grid textual menu
    getMenu: function() {
        var m = [];
        m.push({
            text: _('tvsorter.cmpitem_update')
            ,handler: function() {
                var panel = Ext.getCmp('tvsorter-nav').panel;
                panel.viewTVs(this.menu.record);
            }
            ,scope: this
        });
        return m;
    }
});
Ext.reg('tvsorter-grid-templates', TVSorter.TemplatesGrid);
