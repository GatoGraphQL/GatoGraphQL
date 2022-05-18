<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

trait PoP_Engine_Module_Processor_InnerModules_Trait
{
    public function getInnerSubmodules(array $component): array
    {
        return array();
    }

    public function getSubcomponents(array $component): array
    {
        return array_merge(
            parent::getSubcomponents($component),
            $this->getInnerSubmodules($component)
        );
    }
    
    public function getMutableonmodelConfiguration(array $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getMutableonmodelConfiguration($component, $props);

        if ($subComponents = $this->getInnerSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['inners'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $subComponents
            );
        }
        
        return $ret;
    }
}
