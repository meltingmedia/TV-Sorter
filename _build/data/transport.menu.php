<?php
/**
 * Adds modActions and modMenus into package
 *
 * @var modX $modx
 * @var modAction $action
 * @var modMenu $menu
 * @package tvsorter
 * @subpackage build
 */
$action = $modx->newObject('modAction');
$action->fromArray(array(
    //'id' => 1,
    'namespace' => 'tvsorter',
    'parent' => 0,
    'controller' => 'index',
    'haslayout' => true,
    'lang_topics' => 'tvsorter:default',
    'assets' => '',
), '', true, true);

$menu = $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'tvsorter',
    'parent' => 'components',
    'description' => 'tvsorter.menu_desc',
    'icon' => 'images/icons/plugin.gif',
    'menuindex' => 0,
    'params' => '',
    'handler' => '',
//    'action' => '',
//    'permissions' => '',
//    'namespace' => '',
), '', true, true);
$menu->addOne($action);
unset($menus);

return $menu;