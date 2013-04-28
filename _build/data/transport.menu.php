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
$c = $modx->newQuery('modAction');
$c->sortby('id', 'DESC');
$c->limit(1);
/** @var modAction $last */
$last = $modx->getObject('modAction', $c);
$id = $last->get('id') + 1;

$action = $modx->newObject('modAction');
$action->fromArray(array(
    'id' => $id,
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