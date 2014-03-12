<?php
/**
 * This plugin automatically sets (or fix) the TVs ranks when adding or removing a TV.
 * This is to make sure each TV got a "unique" rank per template.
 *
 * @var modX $modx
 */

if ($modx->context->get('key') != 'mgr') {
    return;
}
$params = $modx->event->params;
/** @var modTemplateVar $tv */
$tv =& $params['tv'];

switch ($modx->event->name) {
    case 'OnTVFormSave':
        if ($params['mode'] === modSystemEvent::MODE_NEW) {
            $category = $tv->get('category');
            $tpls = $tv->getMany('TemplateVarTemplates');
            /** @var modTemplateVarTemplate $tpl */
            foreach ($tpls as $tpl) {
                // Get the actual number of TVs linked to this template for this TV category
                $c = $modx->newQuery('modTemplateVarTemplate');
                $c->rightJoin('modTemplateVar', 'TemplateVar');
                $c->where(array(
                    'TemplateVar.category' => $category,
                    'templateid' => $tpl->get('templateid'),
                    'tmplvarid:!=' => $tv->get('id'),
                ));

                $rank = $modx->getCount('modTemplateVarTemplate', $c);
                $tpl->set('rank', $rank);
                $tpl->save();
            }
        }
        break;
    case 'OnTVFormDelete':
        $category = $tv->get('category');
        $tpls = $tv->getMany('TemplateVarTemplates');
        /** @var modTemplateVarTemplate $tpl */
        foreach ($tpls as $tpl) {
            $rank = $tpl->get('rank');
            // Get the impacted objects...
            $c = $modx->newQuery('modTemplateVarTemplate');
            $c->rightJoin('modTemplateVar', 'TemplateVar');
            $c->where(array(
                'TemplateVar.category' => $category,
                'templateid' => $tpl->get('templateid'),
                'rank:>' => $rank,
            ));
            // ... and decrement their ranks
            $collection = $modx->getCollection('modTemplateVarTemplate', $c);
            /** @var modTemplateVarTemplate $update */
            foreach ($collection as $update) {
                $update->set('rank', ($update->get('rank') - 1));
                $update->save();
            }
        }
        break;
}

return;
