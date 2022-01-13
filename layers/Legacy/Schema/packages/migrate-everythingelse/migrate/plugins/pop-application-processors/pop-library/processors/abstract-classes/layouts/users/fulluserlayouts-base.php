<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_CustomFullUserLayoutsBase extends PoP_Module_Processor_FullUserLayoutsBase
{
    public function getTitleSubmodule(array $module)
    {

        // Allow URE to Change it, to inject the (Organization / Organization+Members) links
        // return \PoP\Root\App::getHookManager()->applyFilters(
        //     'PoP_Module_Processor_CustomFullUserLayoutsBase:title_module',
        //     PoP_Module_Processor_CustomFullUserTitleLayouts::MODULE_LAYOUT_FULLUSERTITLE
        // );
        return [PoP_Module_Processor_CustomFullUserTitleLayouts::class, PoP_Module_Processor_CustomFullUserTitleLayouts::MODULE_LAYOUT_FULLUSERTITLE];
    }

    public function showDescription(array $module, array &$props)
    {

        // Show the description only if configured to show in the body, otherwise it will be a widget
        return PoP_ApplicationProcessors_Utils::authorFulldescription();
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_CLASSES]['sidebar'] = 'col-sm-12';
        $ret[GD_JS_CLASSES]['content-body'] = 'col-sm-12';
        
        return $ret;
    }
}
