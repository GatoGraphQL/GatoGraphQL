<?php

abstract class PoP_Module_Processor_TriggerLocationTypeaheadScriptLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION];
    }

    /**
     * @todo Migrate from string to LeafField
     *
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\LeafField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        return array('id', 'name', 'address', 'coordinates');
    }
}
