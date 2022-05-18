<?php

class PoP_Module_Processor_SegmentedButtonMenuLayouts extends PoP_Module_Processor_SegmentedButtonLayoutsBase
{
    public final const MODULE_LAYOUT_MENU_SEGMENTEDBUTTON = 'layout-menu-segmentedbutton';
    public final const MODULE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON = 'layout-menu-navigatorsegmentedbutton';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_MENU_SEGMENTEDBUTTON],
            [self::class, self::COMPONENT_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON],
        );
    }

    public function getCollapseClass(array $component)
    {
        $ret = parent::getCollapseClass($component);

        // Fix: Comment Leo 29/03/2014: open all collapses immediately
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
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
    public function getDataFields(array $component, array &$props): array
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MENU_SEGMENTEDBUTTON:
            case self::COMPONENT_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                return array('id', 'itemDataEntries(flat:true)@itemDataEntries');
        }

        return parent::getDataFields($component, $props);
    }

    public function getSegmentedbuttonSubmodules(array $component)
    {
        $ret = parent::getSegmentedbuttonSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret[] = [PoP_Module_Processor_SegmentedButtonLinks::class, PoP_Module_Processor_SegmentedButtonLinks::COMPONENT_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR];
                break;
        }

        return $ret;
    }
    public function getDropdownsegmentedbuttonSubmodules(array $component)
    {
        $ret = parent::getDropdownsegmentedbuttonSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret[] = [PoP_Module_Processor_SegmentedButtonLinks::class, PoP_Module_Processor_SegmentedButtonLinks::COMPONENT_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR];
                // $ret[] = [PoP_Module_Processor_SegmentedButtonLinks::class, PoP_Module_Processor_SegmentedButtonLinks::COMPONENT_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR];
                break;
        }

        return $ret;
    }

    public function getBtnClass(array $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MENU_SEGMENTEDBUTTON:
            case self::COMPONENT_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret .= ' btn-background';
                break;
        }

        return $ret;
    }
}



