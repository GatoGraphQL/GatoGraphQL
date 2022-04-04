<?php

class PoP_Module_Processor_SegmentedButtonMenus extends PoP_Module_Processor_ContentsBase
{
    public final const MODULE_SEGMENTEDBUTTONMENU = 'segmentedbuttonmenu';
    public final const MODULE_NAVIGATORSEGMENTEDBUTTONMENU = 'navigatorsegmentedbuttonmenu';
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SEGMENTEDBUTTONMENU],
            [self::class, self::MODULE_NAVIGATORSEGMENTEDBUTTONMENU],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SEGMENTEDBUTTONMENU:
                return [PoP_Module_Processor_MenuContentInners::class, PoP_Module_Processor_MenuContentInners::MODULE_CONTENTINNER_MENU_SEGMENTEDBUTTON];

            case self::MODULE_NAVIGATORSEGMENTEDBUTTONMENU:
                return [PoP_Module_Processor_MenuContentInners::class, PoP_Module_Processor_MenuContentInners::MODULE_CONTENTINNER_MENU_NAVIGATORSEGMENTEDBUTTON];
        }
    }
}


