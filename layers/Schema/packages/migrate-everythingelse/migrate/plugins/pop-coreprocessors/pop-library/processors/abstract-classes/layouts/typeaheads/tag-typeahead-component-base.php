<?php

abstract class PoP_Module_Processor_TagTypeaheadComponentLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTTAG_TYPEAHEAD_COMPONENT];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('symbolnamedescription');
    }
}
