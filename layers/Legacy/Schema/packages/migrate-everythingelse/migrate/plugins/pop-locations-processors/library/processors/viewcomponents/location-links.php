<?php

class PoP_Module_Processor_LocationViewComponentLinks extends PoP_Module_Processor_LocationViewComponentLinksBase
{
    public final const COMPONENT_VIEWCOMPONENT_LINK_LOCATIONICONNAME = 'em-viewcomponent-link-locationiconname';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_LINK_LOCATIONICONNAME],
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_LINK_LOCATIONICONNAME:
                return [PoP_Module_Processor_LocationNameLayouts::class, PoP_Module_Processor_LocationNameLayouts::COMPONENT_EM_LAYOUT_LOCATIONICONNAME];
        }

        return parent::getLayoutSubcomponent($component);
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_LINK_LOCATIONICONNAME:
                return POP_TARGET_MODALS;
        }
        
        return parent::getLinktarget($component, $props);
    }
}



