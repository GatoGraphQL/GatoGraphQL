<?php

abstract class PoP_Module_Processor_EventDateAndTimeLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_EventsCreation_TemplateResourceLoaderProcessor::class, PoP_EventsCreation_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTEVENT_TABLECOL];
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        return array('dates', 'times');
    }
}
