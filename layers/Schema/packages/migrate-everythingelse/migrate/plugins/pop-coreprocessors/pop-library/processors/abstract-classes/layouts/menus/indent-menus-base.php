<?php

abstract class PoP_Module_Processor_IndentMenuLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MENU_INDENT];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('id', 'itemDataEntries');
    }
}
