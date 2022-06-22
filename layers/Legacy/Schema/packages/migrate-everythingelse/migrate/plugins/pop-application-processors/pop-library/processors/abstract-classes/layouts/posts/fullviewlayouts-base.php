<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_CustomFullViewLayoutsBase extends PoP_Module_Processor_FullViewLayoutsBase
{
    public function getTitleSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_CustomFullViewTitleLayouts::class, PoP_Module_Processor_CustomFullViewTitleLayouts::COMPONENT_LAYOUT_FULLVIEWTITLE];
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);

        $ret[GD_JS_CLASSES]['sidebar'] = 'col-sm-12';
        $ret[GD_JS_CLASSES]['content-body'] = 'col-sm-12';
        
        return $ret;
    }
}
