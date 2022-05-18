<?php

abstract class PoP_Module_Processor_LocationViewComponentLinksBase extends PoP_Module_Processor_ViewComponentButtonsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_LOCATIONLINK];
    }

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_LocationNameLayouts::class, PoP_Module_Processor_LocationNameLayouts::MODULE_EM_LAYOUT_LOCATIONICONNAME];
    }

    public function getUrlField(array $componentVariation)
    {
        return 'mapURL';
    }

    public function getLinktarget(array $componentVariation, array &$props)
    {
        return POP_TARGET_MODALS;
    }

    public function getLinkClass(array $componentVariation)
    {
        return 'pop-modalmap-link';
    }
}
