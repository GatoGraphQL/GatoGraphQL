<?php

abstract class PoP_Module_Processor_TagTabPanelSectionBlocksBase extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    // public function getNature(array $componentVariation)
    // {
    //     return TagRequestNature::TAG;
    // }

    protected function getControlgroupBottomSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SUBMENUPOSTLIST];
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'class', 'blockgroup-tagsections');

        parent::initModelProps($componentVariation, $props);
    }
}
