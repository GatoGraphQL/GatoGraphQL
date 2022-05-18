<?php

abstract class PoP_Module_Processor_MapDrawMarkerScriptsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPT_DRAWMARKERS];
    }

    public function getMapdivSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAP_DIV];
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);
        $ret['mapdiv-module'] = $this->getMapdivSubmodule($componentVariation);
        return $ret;
    }
}
