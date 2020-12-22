<?php

class PoP_Module_Processor_Menus extends PoP_Module_Processor_ContentsBase
{
    public const MODULE_DROPDOWNBUTTONMENU_TOP = 'dropdownbuttonmenu-top';
    public const MODULE_DROPDOWNBUTTONMENU_SIDE = 'dropdownbuttonmenu-side';
    public const MODULE_MULTITARGETINDENTMENU = 'multitargetindentmenu';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DROPDOWNBUTTONMENU_TOP],
            [self::class, self::MODULE_DROPDOWNBUTTONMENU_SIDE],
            [self::class, self::MODULE_MULTITARGETINDENTMENU],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONMENU_TOP:
                return [PoP_Module_Processor_MenuContentInners::class, PoP_Module_Processor_MenuContentInners::MODULE_CONTENTINNER_MENU_DROPDOWNBUTTON_TOP];
            
            case self::MODULE_DROPDOWNBUTTONMENU_SIDE:
                return [PoP_Module_Processor_MenuContentInners::class, PoP_Module_Processor_MenuContentInners::MODULE_CONTENTINNER_MENU_DROPDOWNBUTTON_SIDE];

            case self::MODULE_MULTITARGETINDENTMENU:
                return [PoP_Module_Processor_MenuContentInners::class, PoP_Module_Processor_MenuContentInners::MODULE_CONTENTINNER_MENU_MULTITARGETINDENT];
        }

        return getInnerSubmodule($module);
    }
}


