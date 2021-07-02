<?php

abstract class PoP_Module_Processor_AuthorContactLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_AUTHOR_CONTACT];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('contact');
    }
}
