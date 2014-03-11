<?php
// Dev config
$config = dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
if (!file_exists($config)) {
    // Manager config
    $config = dirname(dirname(dirname(__FILE__))) . '/config.core.php';
}
require_once $config;

require_once MODX_CORE_PATH .'config/'. MODX_CONFIG_KEY .'.inc.php';
require_once MODX_CONNECTORS_PATH .'index.php';

/**
 * At this stage, the following should be available
 *
 * @var modX $modx A modX instance
 * @var string $ctx The context key
 * @var string $ml The manager language
 * @var string $connectorRequestClass The connector request class name used to handle the current request
 */

$corePath = $modx->getOption('tvsorter.core_path', null, $modx->getOption('core_path') . 'components/tvsorter/');
/** @var TVSorter $sorter */
$sorter = $modx->getService('tvsorter', 'services.TVsorter', $corePath);

// Handle request
$path = $modx->getOption('processors_path', $sorter->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));
