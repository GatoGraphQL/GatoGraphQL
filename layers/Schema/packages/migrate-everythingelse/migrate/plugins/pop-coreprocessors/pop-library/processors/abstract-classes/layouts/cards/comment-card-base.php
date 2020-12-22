<?php

abstract class PoP_Module_Processor_CommentCardLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTCOMMENT_CARD];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('contentClipped');
    }
}
