<?php

class PoP_AddHighlights_Module_Processor_MySectionBlocks extends PoP_Module_Processor_MySectionBlocksBase
{
    public const MODULE_BLOCK_MYHIGHLIGHTS_TABLE_EDIT = 'block-myhighlights-table-edit';
    public const MODULE_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW = 'block-myhighlights-scroll-fullviewpreview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_MYHIGHLIGHTS_TABLE_EDIT],
            [self::class, self::MODULE_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW => POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS,
            self::MODULE_BLOCK_MYHIGHLIGHTS_TABLE_EDIT => POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_MYHIGHLIGHTS_TABLE_EDIT => [PoP_AddHighlights_Module_Processor_MySectionDataloads::class, PoP_AddHighlights_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYHIGHLIGHTS_TABLE_EDIT],
            self::MODULE_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW => [PoP_AddHighlights_Module_Processor_MySectionDataloads::class, PoP_AddHighlights_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW],
        );

        return $inner_modules[$module[1]];
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_MYHIGHLIGHTS_TABLE_EDIT:
            case self::MODULE_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_AddHighlights_Module_Processor_CustomControlGroups::class, PoP_AddHighlights_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYHIGHLIGHTLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }
}



