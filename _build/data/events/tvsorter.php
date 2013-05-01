<?php
/**
 * @var modX $modx
 */
$events = array();

$ventName = 'OnTVFormSave';
$events[$ventName] = $modx->newObject('modPluginEvent');
$events[$ventName]->fromArray(array(
    'event' => $ventName,
    'priority' => 0,
    'propertyset' => 0,
), '', true, true);

$ventName = 'OnTVFormDelete';
$events[$ventName] = $modx->newObject('modPluginEvent');
$events[$ventName]->fromArray(array(
    'event' => $ventName,
    'priority' => 0,
    'propertyset' => 0,
), '', true, true);

return $events;
