<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_StructureInnersBase extends PoPEngine_QueryDataComponentProcessorBase
{

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    public function getLayoutSubmodules(array $component)
    {
        return array();
    }

    //-------------------------------------------------
    // PUBLIC Overriding Functions
    //-------------------------------------------------

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($layouts = $this->getLayoutSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }

        return $ret;
    }
    
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);

        if ($layouts = $this->getLayoutSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $layouts
            );
        }

        return $ret;
    }
}
