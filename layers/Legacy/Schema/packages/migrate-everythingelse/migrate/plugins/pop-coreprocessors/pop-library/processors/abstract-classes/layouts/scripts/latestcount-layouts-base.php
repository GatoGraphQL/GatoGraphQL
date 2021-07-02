<?php

abstract class PoP_Module_Processor_LatestCountScriptsLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_LATESTCOUNTSCRIPT];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array_merge(
            parent::getDataFields($module, $props),
            array('latestcountsTriggerValues', 'authors', 'tags', 'references')
        );
    }
}
