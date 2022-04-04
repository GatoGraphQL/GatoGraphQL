<?php

class PoP_LocationPosts_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_LOCATIONPOSTS_SCROLL_NAVIGATOR = 'block-locationposts-scroll-navigator';
    public final const MODULE_BLOCK_LOCATIONPOSTS_SCROLL_ADDONS = 'block-locationposts-scroll-addons';
    public final const MODULE_BLOCK_LOCATIONPOSTS_SCROLL_DETAILS = 'block-locationposts-scroll-details';
    public final const MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_DETAILS = 'block-authorlocationposts-scroll-details';
    public final const MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_DETAILS = 'block-taglocationposts-scroll-details';
    public final const MODULE_BLOCK_LOCATIONPOSTS_SCROLL_SIMPLEVIEW = 'block-locationposts-scroll-simpleview';
    public final const MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW = 'block-authorlocationposts-scroll-simpleview';
    public final const MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW = 'block-taglocationposts-scroll-simpleview';
    public final const MODULE_BLOCK_LOCATIONPOSTS_SCROLL_FULLVIEW = 'block-locationposts-scroll-fullview';
    public final const MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW = 'block-authorlocationposts-scroll-fullview';
    public final const MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_FULLVIEW = 'block-taglocationposts-scroll-fullview';
    public final const MODULE_BLOCK_LOCATIONPOSTS_SCROLL_THUMBNAIL = 'block-locationposts-scroll-thumbnail';
    public final const MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL = 'block-authorlocationposts-scroll-thumbnail';
    public final const MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL = 'block-taglocationposts-scroll-thumbnail';
    public final const MODULE_BLOCK_LOCATIONPOSTS_SCROLL_LIST = 'block-locationposts-scroll-list';
    public final const MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_LIST = 'block-authorlocationposts-scroll-list';
    public final const MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_LIST = 'block-taglocationposts-scroll-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_ADDONS],
            [self::class, self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_LIST],

            [self::class, self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_LIST],

            [self::class, self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_DETAILS => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_LIST => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_ADDONS => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_DETAILS => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_FULLVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_LIST => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_NAVIGATOR => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_SIMPLEVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_THUMBNAIL => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_DETAILS => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_FULLVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_LIST => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_NAVIGATOR => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_NAVIGATOR],
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_ADDONS => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_ADDONS],
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_DETAILS => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS],
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_SIMPLEVIEW => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_FULLVIEW => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_THUMBNAIL => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_LIST => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_DETAILS => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS],
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_LIST => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST],
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_DETAILS => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS],
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_FULLVIEW => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_LIST => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_LIST:
                // Allow URE to add the ContentSource switch
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKAUTHORPOSTLIST];

            case self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_LIST:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    public function getLatestcountSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_LIST:
                return [PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_LOCATIONPOSTS];

            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_LIST:
                return [PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_AUTHOR_LOCATIONPOSTS];

            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_LIST:
                return [PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_TAG_LOCATIONPOSTS];
        }

        return parent::getLatestcountSubmodule($module);
    }
}



