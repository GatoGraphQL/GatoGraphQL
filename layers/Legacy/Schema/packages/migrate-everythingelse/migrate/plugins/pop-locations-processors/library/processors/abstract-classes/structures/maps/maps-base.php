<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class GD_EM_Module_Processor_MapsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP];
    }
    
    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);
        $ret[] = $this->getMapdivSubmodule($componentVariation);
        return $ret;
    }

    public function getMapdivSubmodule(array $componentVariation)
    {
    
        // return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAP_DIV];
        return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAPSTATICIMAGE_USERORPOST_DIV];
    }
    
    public function initWebPlatformModelProps(array $componentVariation, array &$props)
    {
        $mapdiv = $this->getMapdivSubmodule($componentVariation);
        $this->mergeJsmethodsProp($mapdiv, $props, array('mapStandalone'));

        parent::initWebPlatformModelProps($componentVariation, $props);
    }
    
    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $mapdiv = $this->getMapdivSubmodule($componentVariation);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-div'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($mapdiv);
        
        return $ret;
    }
}
