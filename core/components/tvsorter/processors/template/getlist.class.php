<?php

class TemplateGetList extends modObjectGetListProcessor
{
    public $classKey = 'modTemplate';
    public $languageTopics = array('tvsorter:default');
    public $defaultSortField = 'Category.category';
    public $defaultSortDirection = 'ASC';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin('modCategory', 'Category');
        $c->leftJoin('modTemplateVarTemplate', 'TemplateVarTemplates');

        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'templatename:LIKE' => '%'.$query.'%',
            ));
        }

        $c->sortby($this->defaultSortField, $this->defaultSortDirection);
        $c->sortby('templatename', $this->defaultSortDirection);

        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
        $c->select(array(
            'category_name' => 'Category.category',
            'total_tvs'=> 'COUNT(TemplateVarTemplates.tmplvarid)',
        ));
        $c->groupby('id', $this->defaultSortDirection);

        return $c;
    }

    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        if (!$objectArray['category_name']) {
            $objectArray['category_name'] = $this->modx->lexicon('none');
        }

        return $objectArray;
    }
}

return 'TemplateGetList';
