<?php

abstract class PoP_Module_Processor_StaticTypeaheadComponentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT];
    }
}
