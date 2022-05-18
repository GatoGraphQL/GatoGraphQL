<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_CustomFullUserLayoutsBase extends PoP_Module_Processor_FullUserLayoutsBase
{
    public function getTitleSubmodule(array $component)
    {

        // Allow URE to Change it, to inject the (Organization / Organization+Members) links
        // return \PoP\Root\App::applyFilters(
        //     'PoP_Module_Processor_CustomFullUserLayoutsBase:title_component',
        //     PoP_Module_Processor_CustomFullUserTitleLayouts::COMPONENT_LAYOUT_FULLUSERTITLE
        // );
        return [PoP_Module_Processor_CustomFullUserTitleLayouts::class, PoP_Module_Processor_CustomFullUserTitleLayouts::COMPONENT_LAYOUT_FULLUSERTITLE];
    }

    public function showDescription(array $component, array &$props)
    {

        // Show the description only if configured to show in the body, otherwise it will be a widget
        return PoP_ApplicationProcessors_Utils::authorFulldescription();
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);

        $ret[GD_JS_CLASSES]['sidebar'] = 'col-sm-12';
        $ret[GD_JS_CLASSES]['content-body'] = 'col-sm-12';
        
        return $ret;
    }
}
