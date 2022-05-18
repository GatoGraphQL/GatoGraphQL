<?php

class PoP_ContentPostLinks_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_LINKS = 'block-links-tabpanel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_LINKS],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_LINKS => [PoP_ContentPostLinks_Module_Processor_SectionTabPanelComponents::class, PoP_ContentPostLinks_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_LINKS],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_LINKS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubmodule($component);
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_LINKS:
                return [PoP_ContentPostLinks_Module_Processor_CustomFilters::class, PoP_ContentPostLinks_Module_Processor_CustomFilters::COMPONENT_FILTER_LINKS];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }
}


