<?php

abstract class PoP_Module_Processor_LocationViewComponentLinksBase extends PoP_Module_Processor_ViewComponentButtonsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_LOCATIONLINK];
    }

    public function getButtoninnerSubcomponent(array $component)
    {
        return [PoP_Module_Processor_LocationNameLayouts::class, PoP_Module_Processor_LocationNameLayouts::COMPONENT_EM_LAYOUT_LOCATIONICONNAME];
    }

    public function getUrlField(array $component)
    {
        return 'mapURL';
    }

    public function getLinktarget(array $component, array &$props)
    {
        return POP_TARGET_MODALS;
    }

    public function getLinkClass(array $component)
    {
        return 'pop-modalmap-link';
    }
}
