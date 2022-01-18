<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Modules\ModuleUtils;

trait PoP_Engine_Module_Processor_InnerModules_Trait
{
    public function getInnerSubmodules(array $module): array
    {
        return array();
    }

    public function getSubmodules(array $module): array
    {
        return array_merge(
            parent::getSubmodules($module),
            $this->getInnerSubmodules($module)
        );
    }
    
    public function getMutableonmodelConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getMutableonmodelConfiguration($module, $props);

        if ($submodules = $this->getInnerSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['inners'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $submodules
            );
        }
        
        return $ret;
    }
}
