<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class GD_EM_Module_Processor_MapInnersBase extends PoP_Module_Processor_StructureInnersBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_INNER];
    }
    
    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        $ret[]= [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::MODULE_MAP_SCRIPT_RESETMARKERS];
        $ret[]= $this->getDrawmarkersSubmodule($module);
        
        return $ret;
    }
    
    public function getDrawmarkersSubmodule(array $module)
    {

        //return [PoP_Module_Processor_MapDrawMarkerScripts::class, PoP_Module_Processor_MapDrawMarkerScripts::MODULE_MAP_SCRIPT_DRAWMARKERS];
        return [PoP_Module_Processor_MapDrawMarkerScripts::class, PoP_Module_Processor_MapDrawMarkerScripts::MODULE_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS];
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);
        
        $drawmarkers = $this->getDrawmarkersSubmodule($module);
        $resetmarkers = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::MODULE_MAP_SCRIPT_RESETMARKERS];
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-drawmarkers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($drawmarkers);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-resetmarkers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($resetmarkers);
        
        return $ret;
    }
}
