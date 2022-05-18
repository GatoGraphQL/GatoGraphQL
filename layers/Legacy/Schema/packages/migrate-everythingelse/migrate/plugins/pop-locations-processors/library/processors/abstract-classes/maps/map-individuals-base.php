<?php

abstract class PoP_Module_Processor_MapIndividualsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_INDIVIDUAL];
    }

    public function getMapscriptSubmodule(array $module)
    {
        return [PoP_Module_Processor_MapScripts::class, PoP_Module_Processor_MapScripts::MODULE_MAP_SCRIPT];
    }

    public function openOnemarkerInfowindow(array $module)
    {
        return true;
    }

    public function getMapdivSubmodule(array $module)
    {
        return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAP_DIV];
        // return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAPSTATICIMAGE_USERORPOST_DIV];
    }

    public function getDrawmarkersSubmodule(array $module)
    {
        return [PoP_Module_Processor_MapDrawMarkerScripts::class, PoP_Module_Processor_MapDrawMarkerScripts::MODULE_MAP_SCRIPT_DRAWMARKERS];
        // return [PoP_Module_Processor_MapDrawMarkerScripts::class, PoP_Module_Processor_MapDrawMarkerScripts::MODULE_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS];
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $ret[] = $this->getMapscriptSubmodule($module);
        $ret[] = $this->getMapdivSubmodule($module);
        $ret[] = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::MODULE_MAP_SCRIPT_RESETMARKERS];
        $ret[] = $this->getDrawmarkersSubmodule($module);

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $map_script = $this->getMapscriptSubmodule($module);
        $map_div = $this->getMapdivSubmodule($module);
        $drawmarkers = $this->getDrawmarkersSubmodule($module);
        $resetmarkers = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::MODULE_MAP_SCRIPT_RESETMARKERS];
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($map_script);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-div'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($map_div);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-drawmarkers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($drawmarkers);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-resetmarkers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($resetmarkers);

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $map_div = $this->getMapdivSubmodule($module);
        $this->setProp($map_div, $props, 'open-onemarker-infowindow', $this->openOnemarkerInfowindow($module));
        parent::initModelProps($module, $props);
    }
}
