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
            }/*,{
                xtype: 'tvsorter-grid-templates'
                ,cls: 'main-wrapper'
                ,preventRender: true
            }*/,{
                xtype: 'modx-grid-template-tv'
                ,cls:'main-wrapper'
                ,preventRender: true
                ,template: 1
            }]
        }]
    });
    TVSorter.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(TVSorter.panel.Home, MODx.Panel, {});
Ext.reg('tvsorter-panel-home', TVSorter.panel.Home);
