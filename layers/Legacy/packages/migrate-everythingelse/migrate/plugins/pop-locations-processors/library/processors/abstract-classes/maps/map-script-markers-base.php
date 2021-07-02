<?php

abstract class PoP_Module_Processor_MapMarkerScriptsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPT_MARKERS];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('id', 'coordinates', 'name', 'address');
    }
}
