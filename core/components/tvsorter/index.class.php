<?php

abstract class TVSorterManagerController extends modExtraManagerController
{
    /**
     * @var TVSorter $tvsorter An instance of the service class
     */
    public $tvsorter;
    /**
     * @var string $jsURL The URL for the JS assets for the manager
     */
    public $jsURL;
    /**
     * @var string $cssURL The URL for the CSS assets for the manager
     */
    public $cssURL;

    /**
     * Get the current modX version
     *
     * @return array
     */
    public static function getModxVersion()
    {
        return @include_once MODX_CORE_PATH . "docs/version.inc.php";
    }

    public function initialize()
    {
        if (!property_exists($this->modx, 'tvsorter')) {
            $path = $this->modx->getOption(
                'tvsorter.core_path',
                null,
                $this->modx->getOption('core_path') . 'components/tvsorter/'
            );
            $this->modx->getService('tvsorter', 'services.TVSorter', $path);
        }
        $this->tvsorter =& $this->modx->tvsorter;
        $this->jsURL = $this->tvsorter->config['mgr_js_url'];
        $this->cssURL = $this->tvsorter->config['mgr_css_url'];
        $this->loadBase();
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
<<<HTML
<script>
    Ext.ns("TVSorter");
    Ext.onReady(function() {
        TVSorter.config = {$this->modx->toJSON($this->tvsorter->config)};
    });
</script>
HTML
        );
    }

    /**
     * Override to support raw HTML
     *
     * @param string $tpl Either the Smarty template or raw HTML
     *
     * @return string
     */
    public function fetchTemplate($tpl)
    {
        if (substr($tpl, -4) === '.tpl') {
            return parent::fetchTemplate($tpl);
        }

        return $tpl;
    }

    /**
     * @inherit
     */
    public function getLanguageTopics()
    {
        return array('tvsorter:default');
    }
}

class IndexManagerController extends TVSorterManagerController
{
    /**
     * @inherit
     */
    public static function getDefaultController()
    {
        $version = self::getModxVersion();
        if (version_compare($version['full_version'], '2.3.0') >= 0) {
            return 'welcome';
        }

        return 'default/welcome';
    }
}
