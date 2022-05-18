<?php

class PoP_UserCommunities_ComponentProcessor_AuthorSectionBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_AUTHORCOMMUNITYMEMBERS = 'block-tabpanel-authorcommunitymembers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCOMMUNITYMEMBERS],
        );
    }

    public function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_AUTHORCOMMUNITYMEMBERS => [PoP_UserCommunities_ComponentProcessor_AuthorSectionTabPanelComponents::class, PoP_UserCommunities_ComponentProcessor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCOMMUNITYMEMBERS],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_TABPANEL_AUTHORCOMMUNITYMEMBERS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORCOMMUNITYMEMBERS];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }
}

