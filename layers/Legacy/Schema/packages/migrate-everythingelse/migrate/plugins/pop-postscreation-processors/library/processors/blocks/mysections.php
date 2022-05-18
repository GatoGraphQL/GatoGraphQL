<?php

class PoP_PostsCreation_Module_Processor_MySectionBlocks extends PoP_Module_Processor_MySectionBlocksBase
{
    public final const MODULE_BLOCK_MYPOSTS_TABLE_EDIT = 'block-myposts-table-edit';
    public final const MODULE_BLOCK_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW = 'block-myposts-scroll-simpleviewpreview';
    public final const MODULE_BLOCK_MYPOSTS_SCROLL_FULLVIEWPREVIEW = 'block-myposts-scroll-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_MYPOSTS_TABLE_EDIT],
            [self::class, self::MODULE_BLOCK_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_BLOCK_MYPOSTS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_MYPOSTS_SCROLL_FULLVIEWPREVIEW => POP_POSTSCREATION_ROUTE_MYPOSTS,
            self::MODULE_BLOCK_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW => POP_POSTSCREATION_ROUTE_MYPOSTS,
            self::MODULE_BLOCK_MYPOSTS_TABLE_EDIT => POP_POSTSCREATION_ROUTE_MYPOSTS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_MYPOSTS_TABLE_EDIT => [PoP_PostsCreation_Module_Processor_MySectionDataloads::class, PoP_PostsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYPOSTS_TABLE_EDIT],
            self::MODULE_BLOCK_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW => [PoP_PostsCreation_Module_Processor_MySectionDataloads::class, PoP_PostsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
            self::MODULE_BLOCK_MYPOSTS_SCROLL_FULLVIEWPREVIEW => [PoP_PostsCreation_Module_Processor_MySectionDataloads::class, PoP_PostsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    protected function getSectionfilterModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_MYPOSTS_TABLE_EDIT:
            case self::MODULE_BLOCK_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_BLOCK_MYPOSTS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_InstantaneousFilters::class, PoP_Module_Processor_InstantaneousFilters::MODULE_INSTANTANEOUSFILTER_POSTSECTIONS];
        }

        return parent::getSectionfilterModule($module);
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_MYPOSTS_TABLE_EDIT:
            case self::MODULE_BLOCK_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_BLOCK_MYPOSTS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYBLOCKPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }
}



