<?php

class PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const COMPONENT_BLOCK_LINKS_SCROLL_NAVIGATOR = 'block-links-scroll-navigator';
    public final const COMPONENT_BLOCK_LINKS_SCROLL_ADDONS = 'block-links-scroll-addons';
    public final const COMPONENT_BLOCK_LINKS_SCROLL_DETAILS = 'block-links-scroll-details';
    public final const COMPONENT_BLOCK_AUTHORLINKS_SCROLL_DETAILS = 'block-authorlinks-scroll-details';
    public final const COMPONENT_BLOCK_TAGLINKS_SCROLL_DETAILS = 'block-taglinks-scroll-details';
    public final const COMPONENT_BLOCK_LINKS_SCROLL_SIMPLEVIEW = 'block-links-scroll-simpleview';
    public final const COMPONENT_BLOCK_AUTHORLINKS_SCROLL_SIMPLEVIEW = 'block-authorlinks-scroll-simpleview';
    public final const COMPONENT_BLOCK_TAGLINKS_SCROLL_SIMPLEVIEW = 'block-taglinks-scroll-simpleview';
    public final const COMPONENT_BLOCK_LINKS_SCROLL_FULLVIEW = 'block-links-scroll-fullview';
    public final const COMPONENT_BLOCK_AUTHORLINKS_SCROLL_FULLVIEW = 'block-authorlinks-scroll-fullview';
    public final const COMPONENT_BLOCK_TAGLINKS_SCROLL_FULLVIEW = 'block-taglinks-scroll-fullview';
    public final const COMPONENT_BLOCK_LINKS_SCROLL_THUMBNAIL = 'block-links-scroll-thumbnail';
    public final const COMPONENT_BLOCK_AUTHORLINKS_SCROLL_THUMBNAIL = 'block-authorlinks-scroll-thumbnail';
    public final const COMPONENT_BLOCK_TAGLINKS_SCROLL_THUMBNAIL = 'block-taglinks-scroll-thumbnail';
    public final const COMPONENT_BLOCK_LINKS_SCROLL_LIST = 'block-links-scroll-list';
    public final const COMPONENT_BLOCK_AUTHORLINKS_SCROLL_LIST = 'block-authorlinks-scroll-list';
    public final const COMPONENT_BLOCK_TAGLINKS_SCROLL_LIST = 'block-taglinks-scroll-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_LINKS_SCROLL_NAVIGATOR],
            [self::class, self::COMPONENT_BLOCK_LINKS_SCROLL_ADDONS],
            [self::class, self::COMPONENT_BLOCK_LINKS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_BLOCK_LINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_BLOCK_LINKS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_BLOCK_LINKS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_BLOCK_LINKS_SCROLL_LIST],
            [self::class, self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_LIST],
            [self::class, self::COMPONENT_BLOCK_TAGLINKS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_BLOCK_TAGLINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_BLOCK_TAGLINKS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_BLOCK_TAGLINKS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_BLOCK_TAGLINKS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_DETAILS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_FULLVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_LIST => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_SIMPLEVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_THUMBNAIL => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_LINKS_SCROLL_ADDONS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_LINKS_SCROLL_DETAILS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_LINKS_SCROLL_FULLVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_LINKS_SCROLL_LIST => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_LINKS_SCROLL_SIMPLEVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_LINKS_SCROLL_THUMBNAIL => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_TAGLINKS_SCROLL_DETAILS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_TAGLINKS_SCROLL_FULLVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_TAGLINKS_SCROLL_LIST => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_TAGLINKS_SCROLL_SIMPLEVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_TAGLINKS_SCROLL_THUMBNAIL => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(array $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_LINKS_SCROLL_NAVIGATOR => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_LINKS_SCROLL_NAVIGATOR],
            self::COMPONENT_BLOCK_LINKS_SCROLL_ADDONS => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_LINKS_SCROLL_ADDONS],
            self::COMPONENT_BLOCK_LINKS_SCROLL_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_LINKS_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_LINKS_SCROLL_SIMPLEVIEW => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_LINKS_SCROLL_SIMPLEVIEW],
            self::COMPONENT_BLOCK_LINKS_SCROLL_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_LINKS_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_LINKS_SCROLL_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_LINKS_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_LINKS_SCROLL_LIST => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_LINKS_SCROLL_LIST],
            self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_SIMPLEVIEW => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW],
            self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_LIST => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_LIST],
            self::COMPONENT_BLOCK_TAGLINKS_SCROLL_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLINKS_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_TAGLINKS_SCROLL_SIMPLEVIEW => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW],
            self::COMPONENT_BLOCK_TAGLINKS_SCROLL_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLINKS_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_TAGLINKS_SCROLL_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_TAGLINKS_SCROLL_LIST => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLINKS_SCROLL_LIST],
        );

        return $inner_components[$component[1]] ?? null;
    }

    protected function getControlgroupTopSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_LIST:
                // Allow URE to add the ContentSource switch
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKAUTHORPOSTLIST];

            case self::COMPONENT_BLOCK_LINKS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_TAGLINKS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_LINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_BLOCK_TAGLINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_BLOCK_LINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_TAGLINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_LINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_TAGLINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_LINKS_SCROLL_LIST:
            case self::COMPONENT_BLOCK_TAGLINKS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKPOSTLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    public function getLatestcountSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_LINKS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_LINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_BLOCK_LINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_LINKS_SCROLL_LIST:
                return [PoP_ContentPostLinks_Module_Processor_SectionLatestCounts::class, PoP_ContentPostLinks_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_POSTLINKS];

            case self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORLINKS_SCROLL_LIST:
                return [PoP_ContentPostLinks_Module_Processor_SectionLatestCounts::class, PoP_ContentPostLinks_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_AUTHOR_POSTLINKS];

            case self::COMPONENT_BLOCK_TAGLINKS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_TAGLINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_BLOCK_TAGLINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_TAGLINKS_SCROLL_LIST:
                return [PoP_ContentPostLinks_Module_Processor_SectionLatestCounts::class, PoP_ContentPostLinks_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_TAG_POSTLINKS];
        }

        return parent::getLatestcountSubcomponent($component);
    }
}



