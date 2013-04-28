<?php

class TVSorterMgrHomeManagerController extends TVSorterManagerController
{

    public function getPageTitle()
    {
        return $this->modx->lexicon('tvsorter');
    }

    public function loadCustomCssJs()
    {
        $this->addJavascript($this->jsURL . 'home/tvs.grid.js');
        $this->addJavascript($this->jsURL . 'home/templates.grid.js');
        $this->addJavascript($this->jsURL . 'home/home.panel.js');

        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                MODx.add("tvsorter-panel-home");
            });
        </script>');
    }

    public function getLanguageTopics()
    {
        return array('tvsorter:default', 'template', 'tv');
    }
}
