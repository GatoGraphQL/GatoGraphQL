<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class GD_EM_Module_Processor_MapsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP];
    }
    
    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);
        $ret[] = $this->getMapdivSubmodule($component);
        return $ret;
    }

    public function getMapdivSubmodule(array $component)
    {
    
        // return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::COMPONENT_MAP_DIV];
        return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::COMPONENT_MAPSTATICIMAGE_USERORPOST_DIV];
    }
    
    public function initWebPlatformModelProps(array $component, array &$props)
    {
        $mapdiv = $this->getMapdivSubmodule($component);
        $this->mergeJsmethodsProp($mapdiv, $props, array('mapStandalone'));

        parent::initWebPlatformModelProps($component, $props);
    }
    
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);

        $mapdiv = $this->getMapdivSubmodule($component);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-div'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($mapdiv);
        
        return $ret;
    }
}
