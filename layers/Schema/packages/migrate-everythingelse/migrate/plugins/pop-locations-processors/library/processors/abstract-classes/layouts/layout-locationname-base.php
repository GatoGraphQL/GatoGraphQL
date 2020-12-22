<?php

abstract class PoP_Module_Processor_LocationNameLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_LOCATIONNAME];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('name');
    }

    public function getFontawesome(array $module, array &$props)
    {
        return null;
    }
}
