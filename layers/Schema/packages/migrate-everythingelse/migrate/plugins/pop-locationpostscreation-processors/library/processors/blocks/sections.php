<?php

class GD_Custom_EM_Module_Processor_MySectionBlocks extends PoP_Module_Processor_MySectionBlocksBase
{
    public const MODULE_BLOCK_MYLOCATIONPOSTS_TABLE_EDIT = 'block-mylocationposts-table-edit';
    public const MODULE_BLOCK_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW = 'block-mylocationposts-scroll-simpleviewpreview';
    public const MODULE_BLOCK_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW = 'block-mylocationposts-scroll-fullviewpreview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_MYLOCATIONPOSTS_TABLE_EDIT],
            [self::class, self::MODULE_BLOCK_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_BLOCK_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
            self::MODULE_BLOCK_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
            self::MODULE_BLOCK_MYLOCATIONPOSTS_TABLE_EDIT => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_MYLOCATIONPOSTS_TABLE_EDIT => [GD_Custom_EM_Module_Processor_MySectionDataloads::class, GD_Custom_EM_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT],
            self::MODULE_BLOCK_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW => [GD_Custom_EM_Module_Processor_MySectionDataloads::class, GD_Custom_EM_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
            self::MODULE_BLOCK_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW => [GD_Custom_EM_Module_Processor_MySectionDataloads::class, GD_Custom_EM_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_MYLOCATIONPOSTS_TABLE_EDIT:
            case self::MODULE_BLOCK_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_BLOCK_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYBLOCKCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }
}



