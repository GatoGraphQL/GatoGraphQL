<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_CustomFullViewLayoutsBase extends PoP_Module_Processor_FullViewLayoutsBase
{
    public function getTitleSubmodule(array $module)
    {
        return [PoP_Module_Processor_CustomFullViewTitleLayouts::class, PoP_Module_Processor_CustomFullViewTitleLayouts::MODULE_LAYOUT_FULLVIEWTITLE];
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
