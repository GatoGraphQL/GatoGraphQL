<?php

abstract class PoP_Module_Processor_AnchorMenuLayoutsBase extends PoP_Module_Processor_MenuLayoutsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MENU_ANCHOR];
    }
}
