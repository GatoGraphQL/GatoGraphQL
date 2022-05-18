<?php

class PoP_UserCommunities_ComponentProcessor_SectionBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_COMMUNITIES = 'block-communities-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYMEMBERS = 'block-mymembers-tabpanel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_COMMUNITIES],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYMEMBERS],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_COMMUNITIES => [PoP_UserCommunities_ComponentProcessor_SectionTabPanelComponents::class, PoP_UserCommunities_ComponentProcessor_SectionTabPanelComponents::MODULE_TABPANEL_COMMUNITIES],
            self::MODULE_BLOCK_TABPANEL_MYMEMBERS => [PoP_UserCommunities_ComponentProcessor_SectionTabPanelComponents::class, PoP_UserCommunities_ComponentProcessor_SectionTabPanelComponents::MODULE_TABPANEL_MYMEMBERS],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_COMMUNITIES:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_USERLIST];

            case self::MODULE_BLOCK_TABPANEL_MYMEMBERS:
                return [GD_URE_Module_Processor_CustomControlGroups::class, GD_URE_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYMEMBERS];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_COMMUNITIES:
                return [GD_URE_Module_Processor_CustomFilters::class, GD_URE_Module_Processor_CustomFilters::MODULE_FILTER_COMMUNITIES];

            case self::MODULE_BLOCK_TABPANEL_MYMEMBERS:
                return [GD_URE_Module_Processor_CustomFilters::class, GD_URE_Module_Processor_CustomFilters::MODULE_FILTER_MYMEMBERS];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }
}
