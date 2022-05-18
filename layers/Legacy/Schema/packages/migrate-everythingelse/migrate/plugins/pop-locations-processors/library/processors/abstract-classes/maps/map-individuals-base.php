<?php

abstract class PoP_Module_Processor_MapIndividualsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_INDIVIDUAL];
    }

    public function getMapscriptSubmodule(array $component)
    {
        return [PoP_Module_Processor_MapScripts::class, PoP_Module_Processor_MapScripts::MODULE_MAP_SCRIPT];
    }

    public function openOnemarkerInfowindow(array $component)
    {
        return true;
    }

    public function getMapdivSubmodule(array $component)
    {
        return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAP_DIV];
        // return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAPSTATICIMAGE_USERORPOST_DIV];
    }

    public function getDrawmarkersSubmodule(array $component)
    {
        return [PoP_Module_Processor_MapDrawMarkerScripts::class, PoP_Module_Processor_MapDrawMarkerScripts::MODULE_MAP_SCRIPT_DRAWMARKERS];
        // return [PoP_Module_Processor_MapDrawMarkerScripts::class, PoP_Module_Processor_MapDrawMarkerScripts::MODULE_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS];
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        $ret[] = $this->getMapscriptSubmodule($component);
        $ret[] = $this->getMapdivSubmodule($component);
        $ret[] = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::MODULE_MAP_SCRIPT_RESETMARKERS];
        $ret[] = $this->getDrawmarkersSubmodule($component);

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $map_script = $this->getMapscriptSubmodule($component);
        $map_div = $this->getMapdivSubmodule($component);
        $drawmarkers = $this->getDrawmarkersSubmodule($component);
        $resetmarkers = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::MODULE_MAP_SCRIPT_RESETMARKERS];
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($map_script);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-div'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($map_div);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-drawmarkers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($drawmarkers);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-resetmarkers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($resetmarkers);

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $map_div = $this->getMapdivSubmodule($component);
        $this->setProp($map_div, $props, 'open-onemarker-infowindow', $this->openOnemarkerInfowindow($component));
        parent::initModelProps($component, $props);
    }
}
