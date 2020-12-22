<?php

class PoP_UserCommunities_ModuleProcessor_AuthorSectionBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public const MODULE_BLOCK_TABPANEL_AUTHORCOMMUNITYMEMBERS = 'block-tabpanel-authorcommunitymembers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCOMMUNITYMEMBERS],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_AUTHORCOMMUNITYMEMBERS => [PoP_UserCommunities_ModuleProcessor_AuthorSectionTabPanelComponents::class, PoP_UserCommunities_ModuleProcessor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCOMMUNITYMEMBERS],
        );
        if ($inner = $inners[$module[1]]) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_AUTHORCOMMUNITYMEMBERS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORCOMMUNITYMEMBERS];
        }
        
        return parent::getDelegatorfilterSubmodule($module);
    }
}

