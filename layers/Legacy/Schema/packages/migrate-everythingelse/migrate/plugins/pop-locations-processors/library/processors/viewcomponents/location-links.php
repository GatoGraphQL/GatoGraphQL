<?php

class PoP_Module_Processor_LocationViewComponentLinks extends PoP_Module_Processor_LocationViewComponentLinksBase
{
    public final const MODULE_VIEWCOMPONENT_LINK_LOCATIONICONNAME = 'em-viewcomponent-link-locationiconname';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_LINK_LOCATIONICONNAME],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_VIEWCOMPONENT_LINK_LOCATIONICONNAME:
                return [PoP_Module_Processor_LocationNameLayouts::class, PoP_Module_Processor_LocationNameLayouts::MODULE_EM_LAYOUT_LOCATIONICONNAME];
        }

        return parent::getLayoutSubmodule($component);
    }

    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_VIEWCOMPONENT_LINK_LOCATIONICONNAME:
                return POP_TARGET_MODALS;
        }
        
        return parent::getLinktarget($component, $props);
    }
}



