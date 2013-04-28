<?php

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH.'config/'. MODX_CONFIG_KEY .'.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('tvsorter.core_path', null, $modx->getOption('core_path') . 'components/tvsorter/');
require_once $corePath . 'model/tvsorter/tvsorter.class.php';
$modx->tvsorter = new TVSorter($modx);

// handle request
$path = $modx->getOption('processors_path', $modx->tvsorter->config, $corePath . 'processors/');
$location = $modx->context->get('key') == 'mgr' ? 'mgr' : '';
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => $location,
));
