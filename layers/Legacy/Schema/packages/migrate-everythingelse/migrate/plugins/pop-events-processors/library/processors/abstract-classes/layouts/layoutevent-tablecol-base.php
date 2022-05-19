<?php

abstract class PoP_Module_Processor_EventDateAndTimeLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_EventsCreation_TemplateResourceLoaderProcessor::class, PoP_EventsCreation_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTEVENT_TABLECOL];
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(array $component, array &$props): array
    {
        return array('dates', 'times');
    }
}
