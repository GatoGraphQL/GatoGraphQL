<?php

class PoP_RelatedPosts_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public const MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_DETAILS = 'block-singlerelatedcontent-scroll-details';
    public const MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW = 'block-singlerelatedcontent-scroll-simpleview';
    public const MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_FULLVIEW = 'block-singlerelatedcontent-scroll-fullview';
    public const MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL = 'block-singlerelatedcontent-scroll-thumbnail';
    public const MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST = 'block-singlerelatedcontent-scroll-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_DETAILS => POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
            self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_FULLVIEW => POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
            self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST => POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
            self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW => POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
            self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL => POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW => [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_FULLVIEW => [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_DETAILS => [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS],
            self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL => [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST => [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST],
        );

        return $inner_modules[$module[1]];
    }

    protected function getSectionfilterModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST:
                if (defined('POP_TAXONOMYQUERY_INITIALIZED') && PoP_Application_TaxonomyQuery_ConfigurationUtils::enableFilterAllcontentByTaxonomy() && PoP_ApplicationProcessors_Utils::addSections()) {
                    return [PoP_Module_Processor_InstantaneousFilters::class, PoP_Module_Processor_InstantaneousFilters::MODULE_INSTANTANEOUSFILTER_CONTENTSECTIONS];
                }
                break;
        }

        return parent::getSectionfilterModule($module);
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    public function getLatestcountSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST:
                return [PoP_Module_Processor_LatestCounts::class, PoP_Module_Processor_LatestCounts::MODULE_LATESTCOUNT_SINGLE_CONTENT];
        }

        return parent::getLatestcountSubmodule($module);
    }
}



