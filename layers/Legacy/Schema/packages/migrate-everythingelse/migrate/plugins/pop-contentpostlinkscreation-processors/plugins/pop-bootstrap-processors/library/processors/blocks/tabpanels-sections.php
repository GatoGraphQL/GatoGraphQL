<?php

class PoP_ContentPostLinksCreation_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_MYLINKS = 'block-mylinks-tabpanel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_MYLINKS],
        );
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_MYLINKS => [PoP_ContentPostLinksCreation_Module_Processor_SectionTabPanelComponents::class, PoP_ContentPostLinksCreation_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYLINKS],
        );
        if ($inner = $inners[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_MYLINKS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }

    public function getDelegatorfilterSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_MYLINKS:
                return [PoP_ContentPostLinksCreation_Module_Processor_CustomFilters::class, PoP_ContentPostLinksCreation_Module_Processor_CustomFilters::MODULE_FILTER_MYLINKS];
        }

        return parent::getDelegatorfilterSubmodule($componentVariation);
    }
}


