<?php

class PoP_Module_Processor_SegmentedButtonMenuLayouts extends PoP_Module_Processor_SegmentedButtonLayoutsBase
{
    public const MODULE_LAYOUT_MENU_SEGMENTEDBUTTON = 'layout-menu-segmentedbutton';
    public const MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON = 'layout-menu-navigatorsegmentedbutton';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MENU_SEGMENTEDBUTTON],
            [self::class, self::MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON],
        );
    }

    public function getCollapseClass(array $module)
    {
        $ret = parent::getCollapseClass($module);

        // Fix: Comment Leo 29/03/2014: open all collapses immediately
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret .= ' in';
                break;
        }

        return $ret;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MENU_SEGMENTEDBUTTON:
            case self::MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                return array('id', 'itemDataEntries(flat:true)@itemDataEntries');
        }

        return parent::getDataFields($module, $props);
    }

    public function getSegmentedbuttonSubmodules(array $module)
    {
        $ret = parent::getSegmentedbuttonSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret[] = [PoP_Module_Processor_SegmentedButtonLinks::class, PoP_Module_Processor_SegmentedButtonLinks::MODULE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR];
                break;
        }

        return $ret;
    }
    public function getDropdownsegmentedbuttonSubmodules(array $module)
    {
        $ret = parent::getDropdownsegmentedbuttonSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret[] = [PoP_Module_Processor_SegmentedButtonLinks::class, PoP_Module_Processor_SegmentedButtonLinks::MODULE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR];
                // $ret[] = [PoP_Module_Processor_SegmentedButtonLinks::class, PoP_Module_Processor_SegmentedButtonLinks::MODULE_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR];
                break;
        }

        return $ret;
    }

    public function getBtnClass(array $module, array &$props)
    {
        $ret = parent::getBtnClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_MENU_SEGMENTEDBUTTON:
            case self::MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret .= ' btn-background';
                break;
        }

        return $ret;
    }
}



