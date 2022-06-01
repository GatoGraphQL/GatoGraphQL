<?php

class PoP_Module_Processor_SegmentedButtonMenuLayouts extends PoP_Module_Processor_SegmentedButtonLayoutsBase
{
    public final const COMPONENT_LAYOUT_MENU_SEGMENTEDBUTTON = 'layout-menu-segmentedbutton';
    public final const COMPONENT_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON = 'layout-menu-navigatorsegmentedbutton';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_MENU_SEGMENTEDBUTTON,
            self::COMPONENT_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON,
        );
    }

    public function getCollapseClass(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getCollapseClass($component);

        // Fix: Comment Leo 29/03/2014: open all collapses immediately
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret .= ' in';
                break;
        }

        return $ret;
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_MENU_SEGMENTEDBUTTON:
            case self::COMPONENT_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                return array('id', 'itemDataEntries(flat:true)@itemDataEntries');
        }

        return parent::getLeafComponentFields($component, $props);
    }

    public function getSegmentedbuttonSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getSegmentedbuttonSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret[] = [PoP_Module_Processor_SegmentedButtonLinks::class, PoP_Module_Processor_SegmentedButtonLinks::COMPONENT_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR];
                break;
        }

        return $ret;
    }
    public function getDropdownsegmentedbuttonSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getDropdownsegmentedbuttonSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret[] = [PoP_Module_Processor_SegmentedButtonLinks::class, PoP_Module_Processor_SegmentedButtonLinks::COMPONENT_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR];
                // $ret[] = [PoP_Module_Processor_SegmentedButtonLinks::class, PoP_Module_Processor_SegmentedButtonLinks::COMPONENT_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR];
                break;
        }

        return $ret;
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_MENU_SEGMENTEDBUTTON:
            case self::COMPONENT_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
                $ret .= ' btn-background';
                break;
        }

        return $ret;
    }
}



