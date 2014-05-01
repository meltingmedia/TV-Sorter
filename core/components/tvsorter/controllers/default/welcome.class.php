<?php

if (!class_exists('TVSorterManagerController')) {
    require_once dirname(dirname(dirname(__FILE__))) . '/index.class.php';
}
class TVSorterWelcomeManagerController extends TVSorterManagerController
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

        $this->addHtml(
<<<HTML
<script>
    Ext.onReady(function() {
        MODx.add('tvsorter-panel-home');
    });
</script>
HTML
        );
    }

    public function getLanguageTopics()
    {
        return array('tvsorter:default', 'template', 'tv');
    }
}

class TVSorterDefaultWelcomeManagerController extends TVSorterWelcomeManagerController
{

}
