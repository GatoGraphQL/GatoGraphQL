<?php

use PoP\ComponentModel\State\ApplicationState;

class UserStance_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const COMPONENT_BLOCK_STANCES_SCROLL_NAVIGATOR = 'block-stances-scroll-navigator';
    public final const COMPONENT_BLOCK_STANCES_SCROLL_ADDONS = 'block-stances-scroll-addons';
    public final const COMPONENT_BLOCK_STANCES_SCROLL_FULLVIEW = 'block-stances-scroll-fullview';
    public final const COMPONENT_BLOCK_STANCES_PRO_SCROLL_FULLVIEW = 'block-stances-pro-scroll-fullview';
    public final const COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_FULLVIEW = 'block-stances-against-scroll-fullview';
    public final const COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_FULLVIEW = 'block-stances-neutral-scroll-fullview';
    public final const COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_FULLVIEW = 'block-stances-pro-general-scroll-fullview';
    public final const COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW = 'block-stances-against-general-scroll-fullview';
    public final const COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW = 'block-stances-neutral-general-scroll-fullview';
    public final const COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW = 'block-stances-pro-article-scroll-fullview';
    public final const COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW = 'block-stances-against-article-scroll-fullview';
    public final const COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW = 'block-stances-neutral-article-scroll-fullview';
    public final const COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_FULLVIEW = 'block-authorstances-scroll-fullview';
    public final const COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_FULLVIEW = 'block-authorstances-pro-scroll-fullview';
    public final const COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW = 'block-authorstances-neutral-scroll-fullview';
    public final const COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW = 'block-authorstances-against-scroll-fullview';
    public final const COMPONENT_BLOCK_TAGSTANCES_SCROLL_FULLVIEW = 'block-tagstances-scroll-fullview';
    public final const COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_FULLVIEW = 'block-tagstances-pro-scroll-fullview';
    public final const COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW = 'block-tagstances-neutral-scroll-fullview';
    public final const COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_FULLVIEW = 'block-tagstances-against-scroll-fullview';
    public final const COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW = 'block-singlerelatedstancecontent-scroll-fullview';
    public final const COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW = 'block-singlerelatedstancecontent-pro-scroll-fullview';
    public final const COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW = 'block-singlerelatedstancecontent-against-scroll-fullview';
    public final const COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW = 'block-singlerelatedstancecontent-neutral-scroll-fullview';
    public final const COMPONENT_BLOCK_STANCES_SCROLL_THUMBNAIL = 'block-stances-scroll-thumbnail';
    public final const COMPONENT_BLOCK_STANCES_PRO_SCROLL_THUMBNAIL = 'block-stances-pro-scroll-thumbnail';
    public final const COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_THUMBNAIL = 'block-stances-against-scroll-thumbnail';
    public final const COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_THUMBNAIL = 'block-stances-neutral-scroll-thumbnail';
    public final const COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL = 'block-stances-pro-general-scroll-thumbnail';
    public final const COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL = 'block-stances-against-general-scroll-thumbnail';
    public final const COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL = 'block-stances-neutral-general-scroll-thumbnail';
    public final const COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL = 'block-stances-pro-article-scroll-thumbnail';
    public final const COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL = 'block-stances-against-article-scroll-thumbnail';
    public final const COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL = 'block-stances-neutral-article-scroll-thumbnail';
    public final const COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_THUMBNAIL = 'block-authorstances-scroll-thumbnail';
    public final const COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL = 'block-authorstances-pro-scroll-thumbnail';
    public final const COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL = 'block-authorstances-neutral-scroll-thumbnail';
    public final const COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL = 'block-authorstances-against-scroll-thumbnail';
    public final const COMPONENT_BLOCK_TAGSTANCES_SCROLL_THUMBNAIL = 'block-tagstances-scroll-thumbnail';
    public final const COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_THUMBNAIL = 'block-tagstances-pro-scroll-thumbnail';
    public final const COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL = 'block-tagstances-neutral-scroll-thumbnail';
    public final const COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL = 'block-tagstances-against-scroll-thumbnail';
    public final const COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL = 'block-singlerelatedstancecontent-scroll-thumbnail';
    public final const COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL = 'block-singlerelatedstancecontent-pro-scroll-thumbnail';
    public final const COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL = 'block-singlerelatedstancecontent-against-scroll-thumbnail';
    public final const COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL = 'block-singlerelatedstancecontent-neutral-scroll-thumbnail';
    public final const COMPONENT_BLOCK_STANCES_SCROLL_LIST = 'block-stances-scroll-list';
    public final const COMPONENT_BLOCK_STANCES_PRO_SCROLL_LIST = 'block-stances-pro-scroll-list';
    public final const COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_LIST = 'block-stances-against-scroll-list';
    public final const COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_LIST = 'block-stances-neutral-scroll-list';
    public final const COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_LIST = 'block-stances-pro-general-scroll-list';
    public final const COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_LIST = 'block-stances-against-general-scroll-list';
    public final const COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_LIST = 'block-stances-neutral-general-scroll-list';
    public final const COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_LIST = 'block-stances-pro-article-scroll-list';
    public final const COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_LIST = 'block-stances-against-article-scroll-list';
    public final const COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST = 'block-stances-neutral-article-scroll-list';
    public final const COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_LIST = 'block-authorstances-scroll-list';
    public final const COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_LIST = 'block-authorstances-pro-scroll-list';
    public final const COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_LIST = 'block-authorstances-neutral-scroll-list';
    public final const COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_LIST = 'block-authorstances-against-scroll-list';
    public final const COMPONENT_BLOCK_TAGSTANCES_SCROLL_LIST = 'block-tagstances-scroll-list';
    public final const COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_LIST = 'block-tagstances-pro-scroll-list';
    public final const COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_LIST = 'block-tagstances-neutral-scroll-list';
    public final const COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_LIST = 'block-tagstances-against-scroll-list';
    public final const COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_LIST = 'block-singlerelatedstancecontent-scroll-list';
    public final const COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST = 'block-singlerelatedstancecontent-pro-scroll-list';
    public final const COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST = 'block-singlerelatedstancecontent-against-scroll-list';
    public final const COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST = 'block-singlerelatedstancecontent-neutral-scroll-list';
    public final const COMPONENT_BLOCK_AUTHORSTANCES_CAROUSEL = 'block-authorstances-carousel';
    public final const COMPONENT_BLOCK_TAGSTANCES_CAROUSEL = 'block-tagstances-carousel';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_STANCES_SCROLL_NAVIGATOR,
            self::COMPONENT_BLOCK_STANCES_SCROLL_ADDONS,
            self::COMPONENT_BLOCK_STANCES_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_STANCES_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_STANCES_SCROLL_LIST,
            self::COMPONENT_BLOCK_STANCES_PRO_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_STANCES_PRO_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_STANCES_PRO_SCROLL_LIST,
            self::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_LIST,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_LIST,
            self::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_LIST,
            self::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_LIST,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_LIST,
            self::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_LIST,
            self::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_LIST,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST,
            self::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_LIST,
            self::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_LIST,
            self::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_LIST,
            self::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_LIST,
            self::COMPONENT_BLOCK_TAGSTANCES_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_TAGSTANCES_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_TAGSTANCES_SCROLL_LIST,
            self::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_LIST,
            self::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_LIST,
            self::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_LIST,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_LIST,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST,
            self::COMPONENT_BLOCK_AUTHORSTANCES_CAROUSEL,
            self::COMPONENT_BLOCK_TAGSTANCES_CAROUSEL,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::COMPONENT_BLOCK_AUTHORSTANCES_CAROUSEL => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
            self::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
            self::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
            self::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
            self::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
            self::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
            self::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
            self::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
            self::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
            self::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
            self::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
            self::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
            self::COMPONENT_BLOCK_STANCES_PRO_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_BLOCK_STANCES_PRO_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_BLOCK_STANCES_PRO_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_BLOCK_STANCES_SCROLL_ADDONS => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_STANCES_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_STANCES_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_STANCES_SCROLL_NAVIGATOR => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_STANCES_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::COMPONENT_BLOCK_TAGSTANCES_CAROUSEL => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_BLOCK_TAGSTANCES_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_TAGSTANCES_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES,
            self::COMPONENT_BLOCK_TAGSTANCES_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_STANCES_SCROLL_NAVIGATOR => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_SCROLL_NAVIGATOR],
            self::COMPONENT_BLOCK_STANCES_SCROLL_ADDONS => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_SCROLL_ADDONS],
            self::COMPONENT_BLOCK_STANCES_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_STANCES_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_STANCES_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_SCROLL_LIST],
            self::COMPONENT_BLOCK_STANCES_PRO_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_PRO_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_STANCES_PRO_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_PRO_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_STANCES_PRO_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_PRO_SCROLL_LIST],
            self::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_AGAINST_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_AGAINST_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_AGAINST_SCROLL_LIST],
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_NEUTRAL_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_NEUTRAL_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_NEUTRAL_SCROLL_LIST],
            self::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_PRO_GENERAL_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST],
            self::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST],
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST],
            self::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST],
            self::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST],
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST],
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST],
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST],
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST],
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST],
            self::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_SCROLL_LIST],
            self::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST],
            self::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST],
            self::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST],
            self::COMPONENT_BLOCK_TAGSTANCES_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_TAGSTANCES_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_TAGSTANCES_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_SCROLL_LIST],
            self::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST],
            self::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST],
            self::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST],
            self::COMPONENT_BLOCK_AUTHORSTANCES_CAROUSEL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_CAROUSEL],
            self::COMPONENT_BLOCK_TAGSTANCES_CAROUSEL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_CAROUSEL],
        );

        return $inner_components[$component->name] ?? null;
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_STANCES_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_STANCES_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_STANCES_SCROLL_LIST:
            case self::COMPONENT_BLOCK_STANCES_PRO_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_STANCES_PRO_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_STANCES_PRO_SCROLL_LIST:
            case self::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_LIST:
            case self::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_LIST:
            case self::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_LIST:
            case self::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_LIST:
            case self::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_LIST:
            case self::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_LIST:
            case self::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_LIST:
            case self::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_LIST:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_LIST:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_LIST:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_LIST:
            case self::COMPONENT_BLOCK_TAGSTANCES_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_TAGSTANCES_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_TAGSTANCES_SCROLL_LIST:
            case self::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_LIST:
            case self::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_LIST:
            case self::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_LIST:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_LIST:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST:
                // For the quickview we return something different
                if (\PoP\Root\App::getState('target') == POP_TARGET_QUICKVIEW) {
                    return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_QUICKVIEWBLOCKPOSTLIST];
                }

                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKPOSTLIST];

            case self::COMPONENT_BLOCK_AUTHORSTANCES_CAROUSEL:
            case self::COMPONENT_BLOCK_TAGSTANCES_CAROUSEL:
                return [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    public function getLatestcountSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_STANCES_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_STANCES_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_STANCES];

            case self::COMPONENT_BLOCK_STANCES_PRO_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_STANCES_PRO_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_STANCES_PRO];

            case self::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_STANCES_AGAINST];

            case self::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_STANCES_NEUTRAL];

            case self::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_AUTHOR_STANCES];

            case self::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_PRO];

            case self::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_NEUTRAL];

            case self::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_AGAINST];

            case self::COMPONENT_BLOCK_TAGSTANCES_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_TAGSTANCES_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_TAG_STANCES];

            case self::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_TAG_STANCES_PRO];

            case self::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_TAG_STANCES_NEUTRAL];

            case self::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_TAG_STANCES_AGAINST];

            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_SINGLE_STANCES];

            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_SINGLE_STANCES_PRO];

            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_SINGLE_STANCES_AGAINST];

            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_SINGLE_STANCES_NEUTRAL];
        }

        return parent::getLatestcountSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_AUTHORSTANCES_CAROUSEL:
            case self::COMPONENT_BLOCK_TAGSTANCES_CAROUSEL:
                // Artificial property added to identify the template when adding component-resources
                $this->appendProp($component, $props, 'class', 'pop-block-carousel block-stances-carousel');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



