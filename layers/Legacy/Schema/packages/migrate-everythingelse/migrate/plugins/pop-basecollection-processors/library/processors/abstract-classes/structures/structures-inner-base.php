<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Modules\ModuleUtils;

abstract class PoP_Module_Processor_StructureInnersBase extends PoPEngine_QueryDataModuleProcessorBase
{

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    public function getLayoutSubmodules(array $module)
    {
        return array();
    }

    //-------------------------------------------------
    // PUBLIC Overriding Functions
    //-------------------------------------------------

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($layouts = $this->getLayoutSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }

        return $ret;
    }
    
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        if ($layouts = $this->getLayoutSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $layouts
            );
        }

        return $ret;
    }
}
