<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class GD_EM_Module_Processor_MapInnersBase extends PoP_Module_Processor_StructureInnersBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_INNER];
    }
    
    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        $ret[]= [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::COMPONENT_MAP_SCRIPT_RESETMARKERS];
        $ret[]= $this->getDrawmarkersSubcomponent($component);
        
        return $ret;
    }
    
    public function getDrawmarkersSubcomponent(array $component)
    {

        //return [PoP_Module_Processor_MapDrawMarkerScripts::class, PoP_Module_Processor_MapDrawMarkerScripts::COMPONENT_MAP_SCRIPT_DRAWMARKERS];
        return [PoP_Module_Processor_MapDrawMarkerScripts::class, PoP_Module_Processor_MapDrawMarkerScripts::COMPONENT_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS];
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);
        
        $drawmarkers = $this->getDrawmarkersSubcomponent($component);
        $resetmarkers = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::COMPONENT_MAP_SCRIPT_RESETMARKERS];
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-script-drawmarkers'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($drawmarkers);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-script-resetmarkers'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($resetmarkers);
        
        return $ret;
    }
}
