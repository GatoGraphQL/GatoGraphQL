<?php

class PoP_UserCommunities_ComponentProcessor_AuthorSectionBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCOMMUNITYMEMBERS = 'block-tabpanel-authorcommunitymembers';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCOMMUNITYMEMBERS,
        );
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCOMMUNITYMEMBERS => [PoP_UserCommunities_ComponentProcessor_AuthorSectionTabPanelComponents::class, PoP_UserCommunities_ComponentProcessor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCOMMUNITYMEMBERS],
        );
        if ($inner = $inners[$component->name] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCOMMUNITYMEMBERS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORCOMMUNITYMEMBERS];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }
}

