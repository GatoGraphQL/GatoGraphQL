<?php

abstract class PoP_Module_Processor_AuthorTabPanelSectionBlocksBase extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    // public function getNature(array $componentVariation)
    // {
    //     return UserRequestNature::USER;
    // }

    protected function getControlgroupBottomSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SUBMENUUSERLIST];
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Artificial property added to identify the template when adding module-resources
        $this->setProp($componentVariation, $props, 'resourceloader', 'blockgroup-authorsections');

        // Needed for the URE ControlSource to show stacked on the right
        $this->appendProp($componentVariation, $props, 'class', 'blockgroup-authorsections');

        parent::initModelProps($componentVariation, $props);
    }
}
