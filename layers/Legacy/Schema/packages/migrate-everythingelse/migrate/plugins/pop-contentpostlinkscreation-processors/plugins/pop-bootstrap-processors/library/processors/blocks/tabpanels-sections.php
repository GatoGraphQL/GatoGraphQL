<?php

class PoP_ContentPostLinksCreation_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_MYLINKS = 'block-mylinks-tabpanel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYLINKS],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_MYLINKS => [PoP_ContentPostLinksCreation_Module_Processor_SectionTabPanelComponents::class, PoP_ContentPostLinksCreation_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYLINKS],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_MYLINKS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($component);
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_MYLINKS:
                return [PoP_ContentPostLinksCreation_Module_Processor_CustomFilters::class, PoP_ContentPostLinksCreation_Module_Processor_CustomFilters::COMPONENT_FILTER_MYLINKS];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }
}


