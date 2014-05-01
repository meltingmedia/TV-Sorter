<?php
// Dev config
$config = dirname(dirname(dirname(__DIR__))) . '/config.core.php';
if (!file_exists($config)) {
    // Manager config
    $config = dirname(dirname(__DIR__)) . '/config.core.php';
}
require_once $config;

require_once MODX_CORE_PATH .'config/'. MODX_CONFIG_KEY .'.inc.php';
require_once MODX_CONNECTORS_PATH .'index.php';

/**
 * @var modX $modx A modX instance
 * @var string $ctx The context key
 * @var string $ml The manager language
 * @var string $connectorRequestClass The connector request class name used to handle the current request
 */

$path = $modx->getOption(
    'tvsorter.core_path',
    null,
    $modx->getOption('core_path') . 'components/tvsorter/'
);
/** @var TVSorter $sorter */
$sorter = $modx->getService('tvsorter', 'services.TVsorter', $path);

// Handle request
$modx->request->handleRequest(array(
    'processors_path' => $modx->getOption('processors_path', $sorter->config, $path . 'processors/'),
    'location' => '',
));
