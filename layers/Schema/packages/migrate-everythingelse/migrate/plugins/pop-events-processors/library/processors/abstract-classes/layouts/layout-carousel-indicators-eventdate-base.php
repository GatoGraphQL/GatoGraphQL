<?php

abstract class PoP_Module_Processor_EventDateCarouselIndicatorLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Events_TemplateResourceLoaderProcessor::class, PoP_Events_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_CAROUSEL_INDICATORS_EVENTDATE];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('startDateReadable');
    }
}
