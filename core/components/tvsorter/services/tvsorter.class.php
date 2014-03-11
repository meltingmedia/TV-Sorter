<?php

/**
 *  TVSorter MODX Service
 */
class TVSorter
{
    /**
     * @var modX $modx A reference to the modX object.
     */
    public $modx;
    /**
     * @var array $config A collection of properties to adjust Object behaviour.
     */
    public $config = array();
    /**
     * @var string $prefix The component prefix, mostly used during dev
     */
    public $prefix;

    /**
     * Constructs the TVSorter object
     *
     * @param modX &$modx A reference to the modX object
     * @param array $config An array of configuration options
     */
    public function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;
        $this->prefix = $prefix = strtolower(get_class($this));

        $basePath = $this->modx->getOption(
            "{$prefix}.core_path",
            $config,
            $this->modx->getOption('core_path') . "components/{$prefix}/"
        );
        $managerUrl = $this->modx->getOption(
            "{$prefix}.manager_url",
            $config,
            $this->modx->getOption('manager_url') . "assets/components/{$prefix}/"
        );

        $this->config = array_merge(array(
            'core_path' => $basePath,
            'processors_path' => $basePath . 'processors/',

            'connector_url' => $managerUrl . 'connector.php',
            'mgr_js_url' => $managerUrl . 'js/',
            'mgr_css_url' => $managerUrl . 'css/',

            'debug' => $this->modx->getOption("{$prefix}.debug", null, false),
            'debug_user' => null,
            'debug_user_id' => null,
        ), $config);

        $this->modx->lexicon->load('tvsorter:default');
        if ($this->modx->getOption('debug', $this->config)) {
            $this->initDebug();
        }
    }

    /**
     * Initialize the debug properties, to get more verbose errors
     *
     * @return void
     */
    private function initDebug()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', true);
        //$this->modx->setLogTarget('FILE');
        $this->modx->setLogLevel(modX::LOG_LEVEL_INFO);
    }
}
