<?php

class PoP_Module_Processor_HomeTabPanelSectionBlocks extends PoP_Module_Processor_HomeTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_HOMECONTENT = 'block-homecontent-tabpanel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_HOMECONTENT],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_HOMECONTENT => [PoP_Module_Processor_HomeSectionTabPanelComponents::class, PoP_Module_Processor_HomeSectionTabPanelComponents::MODULE_TABPANEL_HOMECONTENT],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_HOMECONTENT:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_CONTENT];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_HOMECONTENT:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_HOMECONTENT:
                $this->appendProp($module, $props, 'class', 'pop-home-latesteverything');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


