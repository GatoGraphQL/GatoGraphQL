<?php

abstract class PoP_Module_Processor_AuthorTabPanelSectionBlocksBase extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    // public function getNature(\PoP\ComponentModel\Component\Component $component)
    // {
    //     return UserRequestNature::USER;
    // }

    protected function getControlgroupBottomSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_SUBMENUUSERLIST];
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {

        // Artificial property added to identify the template when adding component-resources
        $this->setProp($component, $props, 'resourceloader', 'blockgroup-authorsections');

        // Needed for the URE ControlSource to show stacked on the right
        $this->appendProp($component, $props, 'class', 'blockgroup-authorsections');

        parent::initModelProps($component, $props);
    }
}
