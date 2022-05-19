<?php

abstract class PoPTheme_Wassup_Module_Processor_PageSectionsBase extends PoP_Engine_Module_Processor_InnerModulesBase
{
    use PoPTheme_Wassup_Module_Processor_PageSectionsTrait;

    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_PAGESECTION_PLAIN];
    }
}
