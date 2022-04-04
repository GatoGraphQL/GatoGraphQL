<?php

class PoP_Module_Processor_LocationViewComponentLinks extends PoP_Module_Processor_LocationViewComponentLinksBase
{
    public final const MODULE_VIEWCOMPONENT_LINK_LOCATIONICONNAME = 'em-viewcomponent-link-locationiconname';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_LINK_LOCATIONICONNAME],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_LINK_LOCATIONICONNAME:
                return [PoP_Module_Processor_LocationNameLayouts::class, PoP_Module_Processor_LocationNameLayouts::MODULE_EM_LAYOUT_LOCATIONICONNAME];
        }

        return parent::getLayoutSubmodule($module);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_LINK_LOCATIONICONNAME:
                return POP_TARGET_MODALS;
        }
        
        return parent::getLinktarget($module, $props);
    }
}



