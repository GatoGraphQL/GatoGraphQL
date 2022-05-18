<?php

class PoP_AddHighlights_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_HIGHLIGHTS = 'block-tabpanel-highlights';
    public final const MODULE_BLOCK_TABPANEL_MYHIGHLIGHTS = 'block-tabpanel-myhighlights';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_HIGHLIGHTS],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYHIGHLIGHTS],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_SectionTabPanelComponents::class, PoP_AddHighlights_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_HIGHLIGHTS],
            self::COMPONENT_BLOCK_TABPANEL_MYHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_SectionTabPanelComponents::class, PoP_AddHighlights_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYHIGHLIGHTS],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_HIGHLIGHTS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_POSTLIST];

            case self::COMPONENT_BLOCK_TABPANEL_MYHIGHLIGHTS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($component);
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_HIGHLIGHTS:
                return [PoP_AddHighlights_Module_Processor_CustomFilters::class, PoP_AddHighlights_Module_Processor_CustomFilters::COMPONENT_FILTER_HIGHLIGHTS];

            case self::COMPONENT_BLOCK_TABPANEL_MYHIGHLIGHTS:
                return [PoP_AddHighlights_Module_Processor_CustomFilters::class, PoP_AddHighlights_Module_Processor_CustomFilters::COMPONENT_FILTER_MYHIGHLIGHTS];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }
}


