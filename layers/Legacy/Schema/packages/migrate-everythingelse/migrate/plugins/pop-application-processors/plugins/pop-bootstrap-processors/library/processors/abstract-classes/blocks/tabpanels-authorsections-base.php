<?php

abstract class PoP_Module_Processor_AuthorTabPanelSectionBlocksBase extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    // public function getNature(array $component)
    // {
    //     return UserRequestNature::USER;
    // }

    protected function getControlgroupBottomSubmodule(array $component)
    {
        return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_SUBMENUUSERLIST];
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Artificial property added to identify the template when adding component-resources
        $this->setProp($component, $props, 'resourceloader', 'blockgroup-authorsections');

        // Needed for the URE ControlSource to show stacked on the right
        $this->appendProp($component, $props, 'class', 'blockgroup-authorsections');

        parent::initModelProps($component, $props);
    }
}
