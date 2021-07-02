<?php
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class GD_EM_Module_Processor_MapsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP];
    }
    
    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
        $ret[] = $this->getMapdivSubmodule($module);
        return $ret;
    }

    public function getMapdivSubmodule(array $module)
    {
    
        // return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAP_DIV];
        return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAPSTATICIMAGE_USERORPOST_DIV];
    }
    
    public function initWebPlatformModelProps(array $module, array &$props)
    {
        $mapdiv = $this->getMapdivSubmodule($module);
        $this->mergeJsmethodsProp($mapdiv, $props, array('mapStandalone'));

        parent::initWebPlatformModelProps($module, $props);
    }
    
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        $mapdiv = $this->getMapdivSubmodule($module);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-div'] = ModuleUtils::getModuleOutputName($mapdiv);
        
        return $ret;
    }
}
