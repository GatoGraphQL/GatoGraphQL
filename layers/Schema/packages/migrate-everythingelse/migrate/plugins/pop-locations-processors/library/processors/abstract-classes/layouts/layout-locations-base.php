<?php

abstract class GD_EM_Module_Processor_LocationLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_LOCATIONS];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('id', 'name', 'address', 'coordinates');
    }
}
