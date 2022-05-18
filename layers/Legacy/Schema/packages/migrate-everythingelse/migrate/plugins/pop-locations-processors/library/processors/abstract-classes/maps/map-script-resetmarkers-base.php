<?php

abstract class PoP_Module_Processor_MapResetMarkerScriptsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPT_RESETMARKERS];
    }
}
