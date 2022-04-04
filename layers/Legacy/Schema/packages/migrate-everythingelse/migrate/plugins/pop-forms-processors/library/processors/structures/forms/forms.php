<?php
use PoP\Engine\Route\RouteUtils;

class PoP_Core_Module_Processor_Forms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_EVERYTHINGQUICKLINKS = 'form-everythingquicklinks';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_EVERYTHINGQUICKLINKS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORM_EVERYTHINGQUICKLINKS:
                return [PoP_Core_Module_Processor_FormInners::class, PoP_Core_Module_Processor_FormInners::MODULE_FORMINNER_EVERYTHINGQUICKLINKS];
        }

        return parent::getInnerSubmodule($module);
    }

    public function getMethod(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORM_EVERYTHINGQUICKLINKS:
                return 'GET';
        }

        return parent::getMethod($module, $props);
    }

    public function getAction(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_FORM_EVERYTHINGQUICKLINKS:
                // Dataload source: search posts (it will never every trigger to fetch this url, it is just a placeholder
                // to trigger using js, eg: typeaheadSearch)
                return RouteUtils::getRouteURL(POP_BLOG_ROUTE_SEARCHCONTENT);
        }

        return parent::getAction($module, $props);
    }
}



