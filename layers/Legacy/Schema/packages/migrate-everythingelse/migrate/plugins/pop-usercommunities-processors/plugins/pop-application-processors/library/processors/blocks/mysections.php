<?php

class PoP_UserCommunities_Module_Processor_MySectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_MYMEMBERS_TABLE_EDIT = 'block-mymembers-table-edit';
    public final const MODULE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW = 'block-mymembers-scroll-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_MYMEMBERS_TABLE_EDIT],
            [self::class, self::MODULE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW => POP_USERCOMMUNITIES_ROUTE_MYMEMBERS,
            self::MODULE_BLOCK_MYMEMBERS_TABLE_EDIT => POP_USERCOMMUNITIES_ROUTE_MYMEMBERS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_BLOCK_MYMEMBERS_TABLE_EDIT => [PoP_UserCommunities_Module_Processor_MySectionDataloads::class, PoP_UserCommunities_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYMEMBERS_TABLE_EDIT],
            self::MODULE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW => [PoP_UserCommunities_Module_Processor_MySectionDataloads::class, PoP_UserCommunities_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW],
        );
        return $inners[$module[1]] ?? null;
    }

    protected function showDisabledLayerIfCheckpointFailed(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_MYMEMBERS_TABLE_EDIT:
            case self::MODULE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW:
                return true;
        }

        return parent::showDisabledLayerIfCheckpointFailed($module, $props);
        ;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_MYMEMBERS_TABLE_EDIT:
            case self::MODULE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW:
                return [GD_URE_Module_Processor_CustomControlGroups::class, GD_URE_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYBLOCKMEMBERS];
        }

        return parent::getControlgroupTopSubmodule($module);
    }
}



