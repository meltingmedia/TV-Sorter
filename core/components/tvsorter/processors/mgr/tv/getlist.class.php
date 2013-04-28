<?php

//require_once MODX_PROCESSORS_PATH . 'element/tv/getlist.class.php';

class ListTVs extends modProcessor
{
    public function process()
    {
        if (!$this->modx->hasPermission(array('view_tv' => true,'view_template' => true))) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $this->modx->lexicon->load('template');

        /* get default properties */
        //$isLimit = !empty($this->getProperty('limit'));
        $start = $this->getProperty('start');
        $limit = $this->getProperty('limit', 20);
        //$sortAlias = $this->modx->getOption('sortAlias',$scriptProperties,'modTemplateVar');
        $sort = $this->getProperty('sort', 'name');
        $dir = $this->getProperty('dir', 'ASC');
        $template = (integer) $this->getProperty('template');
        $category = (integer) $this->getProperty('category', 0);
        $search = $this->getProperty('search');

        if ($template > 0) {
            /** @var modTemplate $templateObj */
            $templateObj = $this->modx->getObject('modTemplate', $template);
        } else {
            $templateObj = $this->modx->newObject('modTemplate');
        }
        $conditions = array();
        if (!empty($category)) {
            $conditions['category'] = $category;
        }
        if (!empty($search)) {
            $conditions['name:LIKE'] = '%'.$search.'%';
            $conditions['OR:description:LIKE'] = '%'.$search.'%';
            $conditions['OR:caption:LIKE'] = '%'.$search.'%';
        }
        //$tvList = $templateObj->getTemplateVarList(array($sort => $dir), $limit, $start, $conditions);
        $tvList = $templateObj->getTemplateVarList(array(
            'Category.category' => 'ASC',
            'access' => 'ASC',
            'tv_rank' => 'ASC',
            'name' => 'ASC'
        ), $limit, $start, $conditions);
        $tvs = $tvList['collection'];
        $count = $tvList['total'];

        /* iterate through tvs */
        $list = array();
        /** @var modTemplateVar $tv */
        foreach ($tvs as $tv) {
            $tvArray = $tv->get(array('id','name','description','tv_rank','category_name'));
            $tvArray['access'] = (boolean) $tv->get('access');
            if (!$tvArray['category_name']) {
                $tvArray['category_name'] = $this->modx->lexicon('none');
            }

            $list[] = $tvArray;
        }

        return $this->outputArray($list, $count);
    }

}

return 'ListTVs';
