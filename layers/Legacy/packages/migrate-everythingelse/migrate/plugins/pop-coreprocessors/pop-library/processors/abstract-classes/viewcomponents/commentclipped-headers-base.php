<?php

abstract class PoP_Module_Processor_CommentClippedViewComponentHeadersBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('contentClipped');
    }
}
