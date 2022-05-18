<?php

abstract class PoP_Module_Processor_MapDrawMarkerScriptsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPT_DRAWMARKERS];
    }

    public function getMapdivSubmodule(array $module)
    {
        return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAP_DIV];
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);
        $ret['mapdiv-module'] = $this->getMapdivSubmodule($module);
        return $ret;
    }
}
