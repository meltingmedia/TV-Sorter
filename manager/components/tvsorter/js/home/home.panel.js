Ext.ns('TVSorter.panel');
/**
 * @class TVSorter.panel.Home
 * @extends MODx.Panel
 * @param {Object} config
 * @xtype tvsorter-panel-home
 */
TVSorter.panel.Home = function(config) {
    config = config || {};

    Ext.apply(config, {
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,defaults: {
            layout: 'anchor'
        }
        ,items: [{
            html: '<h2>' + _('tvsorter.management') + '</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            defaults: {
                autoHeight: true
            }
            ,items: [{
                html: _('tvsorter.management_desc')
                ,border: false
                ,bodyCssClass: 'panel-desc'
            },{
                xtype: 'panel'
                ,layout: 'card'
                ,activeItem: 0
                ,id: 'tvsorter-nav'
                ,panel: this
                ,unstyled: true
                ,items: [{
                     xtype: 'tvsorter-grid-templates'
                     ,cls: 'main-wrapper'
                    ,preventRender: true
                 }]
            }]
        }]
    });
    TVSorter.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(TVSorter.panel.Home, MODx.Panel, {
    viewTVs: function(rec) {
        var card = Ext.getCmp('tvsorter-nav');
        var layout = card.getLayout();
        card.add({
            xtype: 'modx-grid-template-tv'
            ,cls:'main-wrapper'
            ,template: rec.id
            ,templatename: rec.templatename
            ,pageSize: 500
        });

        layout.setActiveItem(1);
    }

    ,viewHome: function() {
        var card = Ext.getCmp('tvsorter-nav');
        var layout = card.getLayout();
        card.getComponent(0).refresh();
        layout.setActiveItem(0);

        card.remove(card.getComponent(1));
    }
});
Ext.reg('tvsorter-panel-home', TVSorter.panel.Home);
