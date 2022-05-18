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

    public function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_HOMECONTENT => [PoP_Module_Processor_HomeSectionTabPanelComponents::class, PoP_Module_Processor_HomeSectionTabPanelComponents::MODULE_TABPANEL_HOMECONTENT],
        );
        if ($inner = $inners[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_HOMECONTENT:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_CONTENT];
        }

        return parent::getDelegatorfilterSubmodule($componentVariation);
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_HOMECONTENT:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_HOMECONTENT:
                $this->appendProp($componentVariation, $props, 'class', 'pop-home-latesteverything');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


