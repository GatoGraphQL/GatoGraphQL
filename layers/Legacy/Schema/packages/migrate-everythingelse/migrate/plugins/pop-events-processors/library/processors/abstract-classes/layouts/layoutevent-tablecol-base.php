<?php

abstract class PoP_Module_Processor_EventDateAndTimeLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_EventsCreation_TemplateResourceLoaderProcessor::class, PoP_EventsCreation_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTEVENT_TABLECOL];
    }

    /**
     * @todo Migrate from string to LeafField
     *
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\LeafField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        return array('dates', 'times');
    }
}
