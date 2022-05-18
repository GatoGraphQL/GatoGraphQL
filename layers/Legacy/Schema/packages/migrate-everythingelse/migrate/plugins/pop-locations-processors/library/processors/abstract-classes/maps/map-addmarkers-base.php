<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_MapAddMarkersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_ADDMARKER];
    }

    public function getSubComponents(array $component): array
    {
        return array(
            $this->getMarkerscriptSubmodule($component),
            $this->getResetmarkerscriptSubmodule($component)
        );
    }

    public function getMarkerscriptSubmodule(array $component)
    {
        return [PoP_Module_Processor_MapMarkerScripts::class, PoP_Module_Processor_MapMarkerScripts::COMPONENT_MAP_SCRIPT_MARKERS];
    }

    public function getResetmarkerscriptSubmodule(array $component)
    {
        return [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::COMPONENT_MAP_SCRIPT_RESETMARKERS];
    }

    public function getDatasetmoduletreeSectionFlattenedDataFields(array $component, array &$props): array
    {
        // Important: Do not bring the data-fields for Add_Marker since they will apply to "post" and not to "location"
        return array();
    }

    // function getModulePath(array $component, array &$props) {
    
    //     return $component;
    // }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $markers = $this->getMarkerscriptSubmodule($component);
        $resetmarkers = $this->getResetmarkerscriptSubmodule($component);

        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-markers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($markers);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-resetmarkers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($resetmarkers);
        
        return $ret;
    }
}
