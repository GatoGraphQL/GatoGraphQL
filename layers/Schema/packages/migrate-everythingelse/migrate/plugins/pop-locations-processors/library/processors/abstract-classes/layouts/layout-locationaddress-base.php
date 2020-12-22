<?php

abstract class PoP_Module_Processor_LocationAddressLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_LOCATIONADDRESS];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('address');
    }
}
