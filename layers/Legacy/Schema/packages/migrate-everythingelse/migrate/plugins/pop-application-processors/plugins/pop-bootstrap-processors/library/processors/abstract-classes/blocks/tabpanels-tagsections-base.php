<?php

abstract class PoP_Module_Processor_TagTabPanelSectionBlocksBase extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    // public function getNature(\PoP\ComponentModel\Component\Component $component)
    // {
    //     return TagRequestNature::TAG;
    // }

    protected function getControlgroupBottomSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_SUBMENUPOSTLIST];
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'blockgroup-tagsections');

        parent::initModelProps($component, $props);
    }
}
