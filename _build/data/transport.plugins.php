<?php
/**
 * @var modX $modx
 * @var array $sources
 */
$plugins = array();
$idx = 0;

$plugins[$idx] = $modx->newObject('modPlugin');
$plugins[$idx]->fromArray(array(
    'id' => $idx + 1,
    'name' => 'TV Sorter',
    'description' => 'This plugin automatically sets (or fix) the TVs ranks when adding or removing a TV.',
    'plugincode' => Helper::getPHPContent($sources['elements'] . 'plugins/tvsorter.php'),
    'category' => 0,
), '', true, true);

$events = include $sources['data'].'events/tvsorter.php';
if (is_array($events) && !empty($events)) {
    $plugins[$idx]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO, 'Packaged in '.count($events).' Plugin Events for TV Sorter.');
    flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not find plugin events for TV Sorter!');
}
unset($events);
$idx += 1;

return $plugins;
