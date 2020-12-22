<?php

abstract class PoP_Module_Processor_LocationViewComponentLinksBase extends PoP_Module_Processor_ViewComponentButtonsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_LOCATIONLINK];
    }

    public function getButtoninnerSubmodule(array $module)
    {
        return [PoP_Module_Processor_LocationNameLayouts::class, PoP_Module_Processor_LocationNameLayouts::MODULE_EM_LAYOUT_LOCATIONICONNAME];
    }

    public function getUrlField(array $module)
    {
        return 'mapURL';
    }

    public function getLinktarget(array $module, array &$props)
    {
        return POP_TARGET_MODALS;
    }

    public function getLinkClass(array $module)
    {
        return 'pop-modalmap-link';
    }
}
