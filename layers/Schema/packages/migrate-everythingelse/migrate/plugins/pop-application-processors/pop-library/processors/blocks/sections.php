<?php

class PoP_Module_Processor_SectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public const MODULE_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS = 'block-singleauthors-scroll-details';
    public const MODULE_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW = 'block-singleauthors-scroll-fullview';
    public const MODULE_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL = 'block-singleauthors-scroll-thumbnail';
    public const MODULE_BLOCK_SINGLEAUTHORS_SCROLL_LIST = 'block-singleauthors-scroll-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS => POP_ROUTE_AUTHORS,
            self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW => POP_ROUTE_AUTHORS,
            self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_LIST => POP_ROUTE_AUTHORS,
            self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL => POP_ROUTE_AUTHORS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS => [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS],
            self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_LIST => [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST],
        );

        return $inner_modules[$module[1]];
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
         // Single Authors has no filter, so show only the Share control
            case self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_SINGLEAUTHORS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SUBMENUSHARE];
        }

        return parent::getControlgroupTopSubmodule($module);
    }
}



