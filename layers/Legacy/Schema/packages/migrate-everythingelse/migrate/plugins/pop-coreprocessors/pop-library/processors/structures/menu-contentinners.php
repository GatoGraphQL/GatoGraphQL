<?php

class PoP_Module_Processor_MenuContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public const MODULE_CONTENTINNER_MENU_BUTTON = 'contentinner-menu-button';
    public const MODULE_CONTENTINNER_MENU_DROPDOWN = 'contentinner-menu-dropdown';
    public const MODULE_CONTENTINNER_MENU_INDENT = 'contentinner-menu-indent';
    public const MODULE_CONTENTINNER_MENU_SEGMENTEDBUTTON = 'contentinner-menu-segmentedbutton';
    public const MODULE_CONTENTINNER_MENU_NAVIGATORSEGMENTEDBUTTON = 'contentinner-menu-navigatorsegmentedbutton';
    public const MODULE_CONTENTINNER_MENU_DROPDOWNBUTTON_TOP = 'contentinner-menu-dropdownbutton-top';
    public const MODULE_CONTENTINNER_MENU_DROPDOWNBUTTON_SIDE = 'contentinner-menu-dropdownbutton-side';
    public const MODULE_CONTENTINNER_MENU_MULTITARGETINDENT = 'contentinner-menu-multitargetindent';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENTINNER_MENU_BUTTON],
            [self::class, self::MODULE_CONTENTINNER_MENU_DROPDOWN],
            [self::class, self::MODULE_CONTENTINNER_MENU_INDENT],
            [self::class, self::MODULE_CONTENTINNER_MENU_SEGMENTEDBUTTON],
            [self::class, self::MODULE_CONTENTINNER_MENU_NAVIGATORSEGMENTEDBUTTON],
            [self::class, self::MODULE_CONTENTINNER_MENU_DROPDOWNBUTTON_TOP],
            [self::class, self::MODULE_CONTENTINNER_MENU_DROPDOWNBUTTON_SIDE],
            [self::class, self::MODULE_CONTENTINNER_MENU_MULTITARGETINDENT],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CONTENTINNER_MENU_BUTTON:
                $ret[] = [PoP_Module_Processor_AnchorMenuLayouts::class, PoP_Module_Processor_AnchorMenuLayouts::MODULE_LAYOUT_MENU_BUTTON];
                break;

            case self::MODULE_CONTENTINNER_MENU_DROPDOWN:
                $ret[] = [PoP_Module_Processor_DropdownMenuLayouts::class, PoP_Module_Processor_DropdownMenuLayouts::MODULE_LAYOUT_MENU_DROPDOWN];
                break;

            case self::MODULE_CONTENTINNER_MENU_DROPDOWNBUTTON_TOP:
                $ret[] = [PoP_Module_Processor_DropdownButtonMenuLayouts::class, PoP_Module_Processor_DropdownButtonMenuLayouts::MODULE_LAYOUT_MENU_DROPDOWNBUTTON_TOP];
                break;

            case self::MODULE_CONTENTINNER_MENU_DROPDOWNBUTTON_SIDE:
                $ret[] = [PoP_Module_Processor_DropdownButtonMenuLayouts::class, PoP_Module_Processor_DropdownButtonMenuLayouts::MODULE_LAYOUT_MENU_DROPDOWNBUTTON_SIDE];
                break;

            case self::MODULE_CONTENTINNER_MENU_INDENT:
                $ret[] = [PoP_Module_Processor_IndentMenuLayouts::class, PoP_Module_Processor_IndentMenuLayouts::MODULE_LAYOUT_MENU_INDENT];
                break;

            case self::MODULE_CONTENTINNER_MENU_SEGMENTEDBUTTON:
                $ret[] = [PoP_Module_Processor_SegmentedButtonMenuLayouts::class, PoP_Module_Processor_SegmentedButtonMenuLayouts::MODULE_LAYOUT_MENU_SEGMENTEDBUTTON];
                break;

            case self::MODULE_CONTENTINNER_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret[] = [PoP_Module_Processor_SegmentedButtonMenuLayouts::class, PoP_Module_Processor_SegmentedButtonMenuLayouts::MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON];
                break;

            case self::MODULE_CONTENTINNER_MENU_MULTITARGETINDENT:
                $ret[] = [PoP_Module_Processor_MultiTargetIndentMenuLayouts::class, PoP_Module_Processor_MultiTargetIndentMenuLayouts::MODULE_LAYOUT_MENU_MULTITARGETINDENT];
                break;
        }

        return $ret;
    }
}


