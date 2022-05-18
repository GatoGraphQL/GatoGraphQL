<?php

abstract class PoP_Module_Processor_MapDrawMarkerScriptsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPT_DRAWMARKERS];
    }

    public function getMapdivSubmodule(array $component)
    {
        return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::COMPONENT_MAP_DIV];
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
        $ret['mapdiv-component'] = $this->getMapdivSubmodule($component);
        return $ret;
    }
}
