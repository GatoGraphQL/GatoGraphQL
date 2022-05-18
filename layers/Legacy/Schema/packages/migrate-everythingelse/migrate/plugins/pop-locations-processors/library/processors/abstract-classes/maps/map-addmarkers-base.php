<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_MapAddMarkersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_ADDMARKER];
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        return array(
            $this->getMarkerscriptSubmodule($componentVariation),
            $this->getResetmarkerscriptSubmodule($componentVariation)
        );
    }

    public function getMarkerscriptSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_MapMarkerScripts::class, PoP_Module_Processor_MapMarkerScripts::MODULE_MAP_SCRIPT_MARKERS];
    }

    public function getResetmarkerscriptSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::MODULE_MAP_SCRIPT_RESETMARKERS];
    }

    public function getDatasetmoduletreeSectionFlattenedDataFields(array $componentVariation, array &$props): array
    {
        // Important: Do not bring the data-fields for Add_Marker since they will apply to "post" and not to "location"
        return array();
    }

    // function getModulePath(array $componentVariation, array &$props) {
    
    //     return $componentVariation;
    // }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $markers = $this->getMarkerscriptSubmodule($componentVariation);
        $resetmarkers = $this->getResetmarkerscriptSubmodule($componentVariation);

        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-markers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($markers);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-resetmarkers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($resetmarkers);
        
        return $ret;
    }
}
