<?php

class PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks extends PoP_Module_Processor_MySectionBlocksBase
{
    public const MODULE_BLOCK_MYLINKS_TABLE_EDIT = 'block-mylinks-table-edit';
    public const MODULE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW = 'block-mylinks-scroll-simpleviewpreview';
    public const MODULE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW = 'block-mylinks-scroll-fullviewpreview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_MYLINKS_TABLE_EDIT],
            [self::class, self::MODULE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW => POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS,
            self::MODULE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW => POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS,
            self::MODULE_BLOCK_MYLINKS_TABLE_EDIT => POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_MYLINKS_TABLE_EDIT => [PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYLINKS_TABLE_EDIT],
            self::MODULE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW => [PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
            self::MODULE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW => [PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYLINKS_SCROLL_FULLVIEWPREVIEW],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_MYLINKS_TABLE_EDIT:
            case self::MODULE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYBLOCKCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }
}



