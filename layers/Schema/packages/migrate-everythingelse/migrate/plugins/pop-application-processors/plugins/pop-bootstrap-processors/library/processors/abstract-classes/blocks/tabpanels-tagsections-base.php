<?php

abstract class PoP_Module_Processor_TagTabPanelSectionBlocksBase extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    // public function getNature(array $module)
    // {
    //     return TagRouteNatures::TAG;
    // }

    protected function getControlgroupBottomSubmodule(array $module)
    {
        return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SUBMENUPOSTLIST];
    }

    public function initModelProps(array $module, array &$props)
    {
        $this->appendProp($module, $props, 'class', 'blockgroup-tagsections');

        parent::initModelProps($module, $props);
    }
}
