<?php

abstract class PoP_Module_Processor_MapIndividualsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_INDIVIDUAL];
    }

    public function getMapscriptSubcomponent(array $component)
    {
        return [PoP_Module_Processor_MapScripts::class, PoP_Module_Processor_MapScripts::COMPONENT_MAP_SCRIPT];
    }

    public function openOnemarkerInfowindow(array $component)
    {
        return true;
    }

    public function getMapdivSubcomponent(array $component)
    {
        return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::COMPONENT_MAP_DIV];
        // return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::COMPONENT_MAPSTATICIMAGE_USERORPOST_DIV];
    }

    public function getDrawmarkersSubcomponent(array $component)
    {
        return [PoP_Module_Processor_MapDrawMarkerScripts::class, PoP_Module_Processor_MapDrawMarkerScripts::COMPONENT_MAP_SCRIPT_DRAWMARKERS];
        // return [PoP_Module_Processor_MapDrawMarkerScripts::class, PoP_Module_Processor_MapDrawMarkerScripts::COMPONENT_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS];
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        $ret[] = $this->getMapscriptSubcomponent($component);
        $ret[] = $this->getMapdivSubcomponent($component);
        $ret[] = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::COMPONENT_MAP_SCRIPT_RESETMARKERS];
        $ret[] = $this->getDrawmarkersSubcomponent($component);

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $map_script = $this->getMapscriptSubcomponent($component);
        $map_div = $this->getMapdivSubcomponent($component);
        $drawmarkers = $this->getDrawmarkersSubcomponent($component);
        $resetmarkers = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::COMPONENT_MAP_SCRIPT_RESETMARKERS];
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-script'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($map_script);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-div'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($map_div);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-script-drawmarkers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($drawmarkers);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-script-resetmarkers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($resetmarkers);

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $map_div = $this->getMapdivSubcomponent($component);
        $this->setProp($map_div, $props, 'open-onemarker-infowindow', $this->openOnemarkerInfowindow($component));
        parent::initModelProps($component, $props);
    }
}
