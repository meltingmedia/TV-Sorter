<?php

if (!class_exists('modTemplateUpdateProcessor')) {
    require_once MODX_PROCESSORS_PATH . 'element/template/update.class.php';
}

class TemplateSave extends modTemplateUpdateProcessor
{

}

return 'TemplateSave';
