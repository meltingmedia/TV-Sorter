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
                xtype: 'tvsorter-grid-templates'
                ,cls: 'main-wrapper'
                ,preventRender: true
            }]
        }]
    });
    TVSorter.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(TVSorter.panel.Home, MODx.Panel, {
    buildLayout: function() {
        var layout = [];
        // Header/title
        layout.push({
            html: '<h2>' + _('tvsorter.management') + '</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        });
        // Tab(s)
        layout.push({
            xtype: 'modx-tabs'
            ,defaults: {
                border: false
                ,autoHeight: true
                ,layout: 'anchor'
            }
            ,border: true
            ,items: this.buildTabs()
        });
        return layout;
    }
    // Build the tabs
    ,buildTabs: function() {
        var tabs = [];
        // Main tab
        tabs.push({
            title: _('tvsorter')
            ,defaults: {
                autoHeight: true
            }
            ,items: [{
                html: _('tvsorter.management_desc')
                ,border: false
                ,bodyCssClass: 'panel-desc'
            },{
                xtype: 'tvsorter-grid-templates'
                ,cls: 'main-wrapper'
                ,preventRender: true
            }]
        });
        return tabs;
    }
});
Ext.reg('tvsorter-panel-home', TVSorter.panel.Home);
