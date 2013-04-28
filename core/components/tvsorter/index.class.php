<?php

require_once dirname(__FILE__) . '/model/tvsorter/tvsorter.class.php';

abstract class TVSorterManagerController extends modManagerController
{
    /** @var TVSorter $tvsorter An instance of the service class */
    public $tvsorter;
    /** @var string $jsURL The URL for the JS assets for the manager */
    public $jsURL;
    /** @var string $cssURL The URL for the CSS assets for the manager */
    public $cssURL;

    public function initialize()
    {
        $this->tvsorter = new TVSorter($this->modx);
        $this->jsURL = $this->tvsorter->config['mgr_js_url'];
        $this->cssURL = $this->tvsorter->config['mgr_css_url'];
        $this->loadBase();
        $this->loadRTE();
        parent::initialize();
    }

    /**
     * Load the "base" required assets
     *
     * @return void
     */
    public function loadBase()
    {
        //$this->addCss($this->tvsorter->config['css_url'] . 'mgr.css');

        $this->addHtml(
'<script type="text/javascript">
    Ext.ns("TVSorter");
    Ext.onReady(function() {
        TVSorter.config = '. $this->modx->toJSON($this->getConfig()) .';
        TVSorter.action = "'. (!empty($_REQUEST['a']) ? $_REQUEST['a'] : 0) .'";
    });
</script>'
        );
    }

    /**
     * Load RTE if enabled
     *
     * @return void
     */
    public function loadRTE()
    {
        if ($this->tvsorter->config['use_rte']) {
            new meltingmedia\rte\Loader(
                $this->modx,
                array(
                    'namespace' => $this->tvsorter->prefix,
                )
            );
        }
    }

    /**
     * Return the component config.
     * Modify this method to unset/remove some sensitive data if any
     *
     * @return array The component config
     */
    public function getConfig()
    {
        return $this->tvsorter->config;
    }

    public function getLanguageTopics()
    {
        return array('tvsorter:default');
    }

    public function checkPermissions()
    {
        return true;
    }

    public function getTemplateFile()
    {
        return '';
    }

    public function process(array $scriptProperties = array())
    {

    }
}

class IndexManagerController extends modExtraManagerController
{
    public static function getDefaultController()
    {
        return 'mgr/home';
    }
}
