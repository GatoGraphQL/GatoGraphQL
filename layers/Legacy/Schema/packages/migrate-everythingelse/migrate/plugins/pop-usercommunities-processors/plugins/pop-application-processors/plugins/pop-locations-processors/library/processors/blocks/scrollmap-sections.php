<?php

class PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionBlocks extends GD_EM_Module_Processor_ScrollMapBlocksBase
{
    public final const MODULE_BLOCK_COMMUNITIES_SCROLLMAP = 'block-communities-scrollmap';
    public final const MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLLMAP = 'block-authormembers-scrollmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_COMMUNITIES_SCROLLMAP],
            [self::class, self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLLMAP => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::MODULE_BLOCK_COMMUNITIES_SCROLLMAP => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_COMMUNITIES_SCROLLMAP => [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP],
            self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLLMAP => [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_COMMUNITIES_SCROLLMAP:
            case self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKUSERLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }
}

