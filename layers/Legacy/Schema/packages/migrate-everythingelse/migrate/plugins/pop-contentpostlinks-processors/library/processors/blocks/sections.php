<?php

class PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public const MODULE_BLOCK_LINKS_SCROLL_NAVIGATOR = 'block-links-scroll-navigator';
    public const MODULE_BLOCK_LINKS_SCROLL_ADDONS = 'block-links-scroll-addons';
    public const MODULE_BLOCK_LINKS_SCROLL_DETAILS = 'block-links-scroll-details';
    public const MODULE_BLOCK_AUTHORLINKS_SCROLL_DETAILS = 'block-authorlinks-scroll-details';
    public const MODULE_BLOCK_TAGLINKS_SCROLL_DETAILS = 'block-taglinks-scroll-details';
    public const MODULE_BLOCK_LINKS_SCROLL_SIMPLEVIEW = 'block-links-scroll-simpleview';
    public const MODULE_BLOCK_AUTHORLINKS_SCROLL_SIMPLEVIEW = 'block-authorlinks-scroll-simpleview';
    public const MODULE_BLOCK_TAGLINKS_SCROLL_SIMPLEVIEW = 'block-taglinks-scroll-simpleview';
    public const MODULE_BLOCK_LINKS_SCROLL_FULLVIEW = 'block-links-scroll-fullview';
    public const MODULE_BLOCK_AUTHORLINKS_SCROLL_FULLVIEW = 'block-authorlinks-scroll-fullview';
    public const MODULE_BLOCK_TAGLINKS_SCROLL_FULLVIEW = 'block-taglinks-scroll-fullview';
    public const MODULE_BLOCK_LINKS_SCROLL_THUMBNAIL = 'block-links-scroll-thumbnail';
    public const MODULE_BLOCK_AUTHORLINKS_SCROLL_THUMBNAIL = 'block-authorlinks-scroll-thumbnail';
    public const MODULE_BLOCK_TAGLINKS_SCROLL_THUMBNAIL = 'block-taglinks-scroll-thumbnail';
    public const MODULE_BLOCK_LINKS_SCROLL_LIST = 'block-links-scroll-list';
    public const MODULE_BLOCK_AUTHORLINKS_SCROLL_LIST = 'block-authorlinks-scroll-list';
    public const MODULE_BLOCK_TAGLINKS_SCROLL_LIST = 'block-taglinks-scroll-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_LINKS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_BLOCK_LINKS_SCROLL_ADDONS],
            [self::class, self::MODULE_BLOCK_LINKS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_LINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_LINKS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_LINKS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_LINKS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORLINKS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_AUTHORLINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORLINKS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORLINKS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTHORLINKS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_TAGLINKS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_TAGLINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_TAGLINKS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_TAGLINKS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_TAGLINKS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_AUTHORLINKS_SCROLL_DETAILS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_AUTHORLINKS_SCROLL_FULLVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_AUTHORLINKS_SCROLL_LIST => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_AUTHORLINKS_SCROLL_SIMPLEVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_AUTHORLINKS_SCROLL_THUMBNAIL => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_LINKS_SCROLL_ADDONS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_LINKS_SCROLL_DETAILS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_LINKS_SCROLL_FULLVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_LINKS_SCROLL_LIST => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_LINKS_SCROLL_SIMPLEVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_LINKS_SCROLL_THUMBNAIL => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_TAGLINKS_SCROLL_DETAILS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_TAGLINKS_SCROLL_FULLVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_TAGLINKS_SCROLL_LIST => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_TAGLINKS_SCROLL_SIMPLEVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_BLOCK_TAGLINKS_SCROLL_THUMBNAIL => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_LINKS_SCROLL_NAVIGATOR => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_NAVIGATOR],
            self::MODULE_BLOCK_LINKS_SCROLL_ADDONS => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_ADDONS],
            self::MODULE_BLOCK_LINKS_SCROLL_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_DETAILS],
            self::MODULE_BLOCK_LINKS_SCROLL_SIMPLEVIEW => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_LINKS_SCROLL_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_LINKS_SCROLL_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_LINKS_SCROLL_LIST => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORLINKS_SCROLL_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORLINKS_SCROLL_DETAILS],
            self::MODULE_BLOCK_AUTHORLINKS_SCROLL_SIMPLEVIEW => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_AUTHORLINKS_SCROLL_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTHORLINKS_SCROLL_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTHORLINKS_SCROLL_LIST => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORLINKS_SCROLL_LIST],
            self::MODULE_BLOCK_TAGLINKS_SCROLL_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS],
            self::MODULE_BLOCK_TAGLINKS_SCROLL_SIMPLEVIEW => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_TAGLINKS_SCROLL_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_TAGLINKS_SCROLL_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_TAGLINKS_SCROLL_LIST => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_LIST],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_AUTHORLINKS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORLINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHORLINKS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORLINKS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORLINKS_SCROLL_LIST:
                // Allow URE to add the ContentSource switch
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKAUTHORPOSTLIST];

            case self::MODULE_BLOCK_LINKS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TAGLINKS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_LINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_TAGLINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_LINKS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGLINKS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_LINKS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_TAGLINKS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_LINKS_SCROLL_LIST:
            case self::MODULE_BLOCK_TAGLINKS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    public function getLatestcountSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_LINKS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_LINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_LINKS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_LINKS_SCROLL_LIST:
                return [PoP_ContentPostLinks_Module_Processor_SectionLatestCounts::class, PoP_ContentPostLinks_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_POSTLINKS];

            case self::MODULE_BLOCK_AUTHORLINKS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORLINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHORLINKS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORLINKS_SCROLL_LIST:
                return [PoP_ContentPostLinks_Module_Processor_SectionLatestCounts::class, PoP_ContentPostLinks_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_AUTHOR_POSTLINKS];

            case self::MODULE_BLOCK_TAGLINKS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TAGLINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_TAGLINKS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGLINKS_SCROLL_LIST:
                return [PoP_ContentPostLinks_Module_Processor_SectionLatestCounts::class, PoP_ContentPostLinks_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_TAG_POSTLINKS];
        }

        return parent::getLatestcountSubmodule($module);
    }
}



