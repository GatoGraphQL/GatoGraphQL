<?php

class PoP_Module_Processor_MenuContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const MODULE_CONTENTINNER_MENU_BUTTON = 'contentinner-menu-button';
    public final const MODULE_CONTENTINNER_MENU_DROPDOWN = 'contentinner-menu-dropdown';
    public final const MODULE_CONTENTINNER_MENU_INDENT = 'contentinner-menu-indent';
    public final const MODULE_CONTENTINNER_MENU_SEGMENTEDBUTTON = 'contentinner-menu-segmentedbutton';
    public final const MODULE_CONTENTINNER_MENU_NAVIGATORSEGMENTEDBUTTON = 'contentinner-menu-navigatorsegmentedbutton';
    public final const MODULE_CONTENTINNER_MENU_DROPDOWNBUTTON_TOP = 'contentinner-menu-dropdownbutton-top';
    public final const MODULE_CONTENTINNER_MENU_DROPDOWNBUTTON_SIDE = 'contentinner-menu-dropdownbutton-side';
    public final const MODULE_CONTENTINNER_MENU_MULTITARGETINDENT = 'contentinner-menu-multitargetindent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTENTINNER_MENU_BUTTON],
            [self::class, self::COMPONENT_CONTENTINNER_MENU_DROPDOWN],
            [self::class, self::COMPONENT_CONTENTINNER_MENU_INDENT],
            [self::class, self::COMPONENT_CONTENTINNER_MENU_SEGMENTEDBUTTON],
            [self::class, self::COMPONENT_CONTENTINNER_MENU_NAVIGATORSEGMENTEDBUTTON],
            [self::class, self::COMPONENT_CONTENTINNER_MENU_DROPDOWNBUTTON_TOP],
            [self::class, self::COMPONENT_CONTENTINNER_MENU_DROPDOWNBUTTON_SIDE],
            [self::class, self::COMPONENT_CONTENTINNER_MENU_MULTITARGETINDENT],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_CONTENTINNER_MENU_BUTTON:
                $ret[] = [PoP_Module_Processor_AnchorMenuLayouts::class, PoP_Module_Processor_AnchorMenuLayouts::COMPONENT_LAYOUT_MENU_BUTTON];
                break;

            case self::COMPONENT_CONTENTINNER_MENU_DROPDOWN:
                $ret[] = [PoP_Module_Processor_DropdownMenuLayouts::class, PoP_Module_Processor_DropdownMenuLayouts::COMPONENT_LAYOUT_MENU_DROPDOWN];
                break;

            case self::COMPONENT_CONTENTINNER_MENU_DROPDOWNBUTTON_TOP:
                $ret[] = [PoP_Module_Processor_DropdownButtonMenuLayouts::class, PoP_Module_Processor_DropdownButtonMenuLayouts::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_TOP];
                break;

            case self::COMPONENT_CONTENTINNER_MENU_DROPDOWNBUTTON_SIDE:
                $ret[] = [PoP_Module_Processor_DropdownButtonMenuLayouts::class, PoP_Module_Processor_DropdownButtonMenuLayouts::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_SIDE];
                break;

            case self::COMPONENT_CONTENTINNER_MENU_INDENT:
                $ret[] = [PoP_Module_Processor_IndentMenuLayouts::class, PoP_Module_Processor_IndentMenuLayouts::COMPONENT_LAYOUT_MENU_INDENT];
                break;

            case self::COMPONENT_CONTENTINNER_MENU_SEGMENTEDBUTTON:
                $ret[] = [PoP_Module_Processor_SegmentedButtonMenuLayouts::class, PoP_Module_Processor_SegmentedButtonMenuLayouts::COMPONENT_LAYOUT_MENU_SEGMENTEDBUTTON];
                break;

            case self::COMPONENT_CONTENTINNER_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret[] = [PoP_Module_Processor_SegmentedButtonMenuLayouts::class, PoP_Module_Processor_SegmentedButtonMenuLayouts::COMPONENT_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON];
                break;

            case self::COMPONENT_CONTENTINNER_MENU_MULTITARGETINDENT:
                $ret[] = [PoP_Module_Processor_MultiTargetIndentMenuLayouts::class, PoP_Module_Processor_MultiTargetIndentMenuLayouts::COMPONENT_LAYOUT_MENU_MULTITARGETINDENT];
                break;
        }

        return $ret;
    }
}


