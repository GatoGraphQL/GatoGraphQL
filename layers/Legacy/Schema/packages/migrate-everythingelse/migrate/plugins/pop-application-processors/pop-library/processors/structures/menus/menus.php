<?php

class PoP_Module_Processor_Menus extends PoP_Module_Processor_ContentsBase
{
    public final const COMPONENT_DROPDOWNBUTTONMENU_TOP = 'dropdownbuttonmenu-top';
    public final const COMPONENT_DROPDOWNBUTTONMENU_SIDE = 'dropdownbuttonmenu-side';
    public final const COMPONENT_MULTITARGETINDENTMENU = 'multitargetindentmenu';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DROPDOWNBUTTONMENU_TOP,
            self::COMPONENT_DROPDOWNBUTTONMENU_SIDE,
            self::COMPONENT_MULTITARGETINDENTMENU,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONMENU_TOP:
                return [PoP_Module_Processor_MenuContentInners::class, PoP_Module_Processor_MenuContentInners::COMPONENT_CONTENTINNER_MENU_DROPDOWNBUTTON_TOP];
            
            case self::COMPONENT_DROPDOWNBUTTONMENU_SIDE:
                return [PoP_Module_Processor_MenuContentInners::class, PoP_Module_Processor_MenuContentInners::COMPONENT_CONTENTINNER_MENU_DROPDOWNBUTTON_SIDE];

            case self::COMPONENT_MULTITARGETINDENTMENU:
                return [PoP_Module_Processor_MenuContentInners::class, PoP_Module_Processor_MenuContentInners::COMPONENT_CONTENTINNER_MENU_MULTITARGETINDENT];
        }

        return getInnerSubcomponent($component);
    }
}


