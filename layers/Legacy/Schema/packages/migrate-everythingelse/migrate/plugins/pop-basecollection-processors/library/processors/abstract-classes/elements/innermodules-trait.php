<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

trait PoP_Engine_Module_Processor_InnerModules_Trait
{
    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array();
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array_merge(
            parent::getSubcomponents($component),
            $this->getInnerSubcomponents($component)
        );
    }
    
    public function getMutableonmodelConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getMutableonmodelConfiguration($component, $props);

        if ($subcomponents = $this->getInnerSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['inners'] = array_map(
                \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $subcomponents
            );
        }
        
        return $ret;
    }
}
