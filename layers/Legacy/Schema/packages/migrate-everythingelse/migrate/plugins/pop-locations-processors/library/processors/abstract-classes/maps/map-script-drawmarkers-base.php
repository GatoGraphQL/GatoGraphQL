<?php

abstract class PoP_Module_Processor_MapDrawMarkerScriptsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPT_DRAWMARKERS];
    }

    public function getMapdivSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::COMPONENT_MAP_DIV];
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
        $ret['mapdiv-component'] = $this->getMapdivSubcomponent($component);
        return $ret;
    }
}
