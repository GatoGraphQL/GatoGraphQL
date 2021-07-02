<?php

abstract class Wassup_Module_Processor_LinkAccessLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_ApplicationProcessors_TemplateResourceLoaderProcessor::class, PoP_ApplicationProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_LINK_ACCESS];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('linkAccessByName');
    }
}
