<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_MapAddMarkersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_ADDMARKER];
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array(
            $this->getMarkerscriptSubcomponent($component),
            $this->getResetmarkerscriptSubcomponent($component)
        );
    }

    public function getMarkerscriptSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_MapMarkerScripts::class, PoP_Module_Processor_MapMarkerScripts::COMPONENT_MAP_SCRIPT_MARKERS];
    }

    public function getResetmarkerscriptSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::COMPONENT_MAP_SCRIPT_RESETMARKERS];
    }

    public function getDatasetcomponentTreeSectionFlattenedDataFields(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        // Important: Do not bring the data-fields for Add_Marker since they will apply to "post" and not to "location"
        return array();
    }

    // function getComponentPath(\PoP\ComponentModel\Component\Component $component, array &$props) {
    
    //     return $component;
    // }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $markers = $this->getMarkerscriptSubcomponent($component);
        $resetmarkers = $this->getResetmarkerscriptSubcomponent($component);

        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-script-markers'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($markers);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-script-resetmarkers'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($resetmarkers);
        
        return $ret;
    }
}
