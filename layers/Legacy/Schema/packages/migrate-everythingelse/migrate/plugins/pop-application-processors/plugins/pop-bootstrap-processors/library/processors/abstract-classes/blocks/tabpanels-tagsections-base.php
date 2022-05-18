<?php

abstract class PoP_Module_Processor_TagTabPanelSectionBlocksBase extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    // public function getNature(array $component)
    // {
    //     return TagRequestNature::TAG;
    // }

    protected function getControlgroupBottomSubmodule(array $component)
    {
        return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SUBMENUPOSTLIST];
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'blockgroup-tagsections');

        parent::initModelProps($component, $props);
    }
}
