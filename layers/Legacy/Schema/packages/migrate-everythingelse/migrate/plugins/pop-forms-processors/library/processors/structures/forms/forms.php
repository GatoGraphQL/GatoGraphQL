<?php
use PoP\Engine\Route\RouteUtils;

class PoP_Core_Module_Processor_Forms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_EVERYTHINGQUICKLINKS = 'form-everythingquicklinks';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORM_EVERYTHINGQUICKLINKS,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORM_EVERYTHINGQUICKLINKS:
                return [PoP_Core_Module_Processor_FormInners::class, PoP_Core_Module_Processor_FormInners::COMPONENT_FORMINNER_EVERYTHINGQUICKLINKS];
        }

        return parent::getInnerSubcomponent($component);
    }

    public function getMethod(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORM_EVERYTHINGQUICKLINKS:
                return 'GET';
        }

        return parent::getMethod($component, $props);
    }

    public function getAction(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component->name) {
            case self::COMPONENT_FORM_EVERYTHINGQUICKLINKS:
                // Dataload source: search posts (it will never every trigger to fetch this url, it is just a placeholder
                // to trigger using js, eg: typeaheadSearch)
                return RouteUtils::getRouteURL(POP_BLOG_ROUTE_SEARCHCONTENT);
        }

        return parent::getAction($component, $props);
    }
}



