<?php

abstract class PoP_Module_Processor_LocationViewComponentLinksBase extends PoP_Module_Processor_ViewComponentButtonsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_LOCATIONLINK];
    }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_LocationNameLayouts::class, PoP_Module_Processor_LocationNameLayouts::COMPONENT_EM_LAYOUT_LOCATIONICONNAME];
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        return 'mapURL';
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return POP_TARGET_MODALS;
    }

    public function getLinkClass(\PoP\ComponentModel\Component\Component $component)
    {
        return 'pop-modalmap-link';
    }
}
