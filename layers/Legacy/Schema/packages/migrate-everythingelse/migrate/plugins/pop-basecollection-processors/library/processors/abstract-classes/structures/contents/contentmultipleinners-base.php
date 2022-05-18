<?php

abstract class PoP_Module_Processor_ContentMultipleInnersBase extends PoP_Module_Processor_StructureInnersBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_CONTENTMULTIPLE_INNER];
    }
}
