<?php

class PoP_Module_Processor_SegmentedButtonMenuLayouts extends PoP_Module_Processor_SegmentedButtonLayoutsBase
{
    public final const MODULE_LAYOUT_MENU_SEGMENTEDBUTTON = 'layout-menu-segmentedbutton';
    public final const MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON = 'layout-menu-navigatorsegmentedbutton';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MENU_SEGMENTEDBUTTON],
            [self::class, self::MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON],
        );
    }

    public function getCollapseClass(array $componentVariation)
    {
        $ret = parent::getCollapseClass($componentVariation);

        // Fix: Comment Leo 29/03/2014: open all collapses immediately
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret .= ' in';
                break;
        }

        return $ret;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_MENU_SEGMENTEDBUTTON:
            case self::MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                return array('id', 'itemDataEntries(flat:true)@itemDataEntries');
        }

        return parent::getDataFields($componentVariation, $props);
    }

    public function getSegmentedbuttonSubmodules(array $componentVariation)
    {
        $ret = parent::getSegmentedbuttonSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret[] = [PoP_Module_Processor_SegmentedButtonLinks::class, PoP_Module_Processor_SegmentedButtonLinks::MODULE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR];
                break;
        }

        return $ret;
    }
    public function getDropdownsegmentedbuttonSubmodules(array $componentVariation)
    {
        $ret = parent::getDropdownsegmentedbuttonSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret[] = [PoP_Module_Processor_SegmentedButtonLinks::class, PoP_Module_Processor_SegmentedButtonLinks::MODULE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR];
                // $ret[] = [PoP_Module_Processor_SegmentedButtonLinks::class, PoP_Module_Processor_SegmentedButtonLinks::MODULE_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR];
                break;
        }

        return $ret;
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBtnClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_MENU_SEGMENTEDBUTTON:
            case self::MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret .= ' btn-background';
                break;
        }

        return $ret;
    }
}



