<?php

abstract class PoP_Module_Processor_PostDateLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTPOST_DATE];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('date');
    }
}
