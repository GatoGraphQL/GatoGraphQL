<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

trait PoP_Engine_Module_Processor_InnerModules_Trait
{
    public function getInnerSubmodules(array $componentVariation): array
    {
        return array();
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        return array_merge(
            parent::getSubComponentVariations($componentVariation),
            $this->getInnerSubmodules($componentVariation)
        );
    }
    
    public function getMutableonmodelConfiguration(array $componentVariation, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getMutableonmodelConfiguration($componentVariation, $props);

        if ($submodules = $this->getInnerSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['inners'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $submodules
            );
        }
        
        return $ret;
    }
}
