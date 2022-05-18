<?php

abstract class PoP_Module_Processor_MapStaticImageURLParamsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_STATICIMAGE_URLPARAM];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        return array('coordinates');
    }
}
