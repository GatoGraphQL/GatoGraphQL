<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_MapAddMarkersBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_ADDMARKER];
    }

    public function getSubmodules(array $module): array
    {
        return array(
            $this->getMarkerscriptSubmodule($module),
            $this->getResetmarkerscriptSubmodule($module)
        );
    }

    public function getMarkerscriptSubmodule(array $module)
    {
        return [PoP_Module_Processor_MapMarkerScripts::class, PoP_Module_Processor_MapMarkerScripts::MODULE_MAP_SCRIPT_MARKERS];
    }

    public function getResetmarkerscriptSubmodule(array $module)
    {
        return [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::MODULE_MAP_SCRIPT_RESETMARKERS];
    }

    public function getDatasetmoduletreeSectionFlattenedDataFields(array $module, array &$props): array
    {
        // Important: Do not bring the data-fields for Add_Marker since they will apply to "post" and not to "location"
        return array();
    }

    // function getModulePath(array $module, array &$props) {
    
    //     return $module;
    // }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $markers = $this->getMarkerscriptSubmodule($module);
        $resetmarkers = $this->getResetmarkerscriptSubmodule($module);

        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-markers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($markers);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-resetmarkers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($resetmarkers);
        
        return $ret;
    }
}
