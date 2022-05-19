<?php

abstract class PoP_Module_Processor_EventDateCarouselIndicatorLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Events_TemplateResourceLoaderProcessor::class, PoP_Events_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_CAROUSEL_INDICATORS_EVENTDATE];
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(array $component, array &$props): array
    {
        return array('startDateReadable');
    }
}
