<?php

use PoP\ComponentModel\State\ApplicationState;

class UserStance_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public const MODULE_BLOCK_STANCES_SCROLL_NAVIGATOR = 'block-stances-scroll-navigator';
    public const MODULE_BLOCK_STANCES_SCROLL_ADDONS = 'block-stances-scroll-addons';
    public const MODULE_BLOCK_STANCES_SCROLL_FULLVIEW = 'block-stances-scroll-fullview';
    public const MODULE_BLOCK_STANCES_PRO_SCROLL_FULLVIEW = 'block-stances-pro-scroll-fullview';
    public const MODULE_BLOCK_STANCES_AGAINST_SCROLL_FULLVIEW = 'block-stances-against-scroll-fullview';
    public const MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_FULLVIEW = 'block-stances-neutral-scroll-fullview';
    public const MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_FULLVIEW = 'block-stances-pro-general-scroll-fullview';
    public const MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW = 'block-stances-against-general-scroll-fullview';
    public const MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW = 'block-stances-neutral-general-scroll-fullview';
    public const MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW = 'block-stances-pro-article-scroll-fullview';
    public const MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW = 'block-stances-against-article-scroll-fullview';
    public const MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW = 'block-stances-neutral-article-scroll-fullview';
    public const MODULE_BLOCK_AUTHORSTANCES_SCROLL_FULLVIEW = 'block-authorstances-scroll-fullview';
    public const MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_FULLVIEW = 'block-authorstances-pro-scroll-fullview';
    public const MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW = 'block-authorstances-neutral-scroll-fullview';
    public const MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW = 'block-authorstances-against-scroll-fullview';
    public const MODULE_BLOCK_TAGSTANCES_SCROLL_FULLVIEW = 'block-tagstances-scroll-fullview';
    public const MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_FULLVIEW = 'block-tagstances-pro-scroll-fullview';
    public const MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW = 'block-tagstances-neutral-scroll-fullview';
    public const MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_FULLVIEW = 'block-tagstances-against-scroll-fullview';
    public const MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW = 'block-singlerelatedstancecontent-scroll-fullview';
    public const MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW = 'block-singlerelatedstancecontent-pro-scroll-fullview';
    public const MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW = 'block-singlerelatedstancecontent-against-scroll-fullview';
    public const MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW = 'block-singlerelatedstancecontent-neutral-scroll-fullview';
    public const MODULE_BLOCK_STANCES_SCROLL_THUMBNAIL = 'block-stances-scroll-thumbnail';
    public const MODULE_BLOCK_STANCES_PRO_SCROLL_THUMBNAIL = 'block-stances-pro-scroll-thumbnail';
    public const MODULE_BLOCK_STANCES_AGAINST_SCROLL_THUMBNAIL = 'block-stances-against-scroll-thumbnail';
    public const MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_THUMBNAIL = 'block-stances-neutral-scroll-thumbnail';
    public const MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL = 'block-stances-pro-general-scroll-thumbnail';
    public const MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL = 'block-stances-against-general-scroll-thumbnail';
    public const MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL = 'block-stances-neutral-general-scroll-thumbnail';
    public const MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL = 'block-stances-pro-article-scroll-thumbnail';
    public const MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL = 'block-stances-against-article-scroll-thumbnail';
    public const MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL = 'block-stances-neutral-article-scroll-thumbnail';
    public const MODULE_BLOCK_AUTHORSTANCES_SCROLL_THUMBNAIL = 'block-authorstances-scroll-thumbnail';
    public const MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL = 'block-authorstances-pro-scroll-thumbnail';
    public const MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL = 'block-authorstances-neutral-scroll-thumbnail';
    public const MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL = 'block-authorstances-against-scroll-thumbnail';
    public const MODULE_BLOCK_TAGSTANCES_SCROLL_THUMBNAIL = 'block-tagstances-scroll-thumbnail';
    public const MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_THUMBNAIL = 'block-tagstances-pro-scroll-thumbnail';
    public const MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL = 'block-tagstances-neutral-scroll-thumbnail';
    public const MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL = 'block-tagstances-against-scroll-thumbnail';
    public const MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL = 'block-singlerelatedstancecontent-scroll-thumbnail';
    public const MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL = 'block-singlerelatedstancecontent-pro-scroll-thumbnail';
    public const MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL = 'block-singlerelatedstancecontent-against-scroll-thumbnail';
    public const MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL = 'block-singlerelatedstancecontent-neutral-scroll-thumbnail';
    public const MODULE_BLOCK_STANCES_SCROLL_LIST = 'block-stances-scroll-list';
    public const MODULE_BLOCK_STANCES_PRO_SCROLL_LIST = 'block-stances-pro-scroll-list';
    public const MODULE_BLOCK_STANCES_AGAINST_SCROLL_LIST = 'block-stances-against-scroll-list';
    public const MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_LIST = 'block-stances-neutral-scroll-list';
    public const MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_LIST = 'block-stances-pro-general-scroll-list';
    public const MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_LIST = 'block-stances-against-general-scroll-list';
    public const MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_LIST = 'block-stances-neutral-general-scroll-list';
    public const MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_LIST = 'block-stances-pro-article-scroll-list';
    public const MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_LIST = 'block-stances-against-article-scroll-list';
    public const MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST = 'block-stances-neutral-article-scroll-list';
    public const MODULE_BLOCK_AUTHORSTANCES_SCROLL_LIST = 'block-authorstances-scroll-list';
    public const MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_LIST = 'block-authorstances-pro-scroll-list';
    public const MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_LIST = 'block-authorstances-neutral-scroll-list';
    public const MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_LIST = 'block-authorstances-against-scroll-list';
    public const MODULE_BLOCK_TAGSTANCES_SCROLL_LIST = 'block-tagstances-scroll-list';
    public const MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_LIST = 'block-tagstances-pro-scroll-list';
    public const MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_LIST = 'block-tagstances-neutral-scroll-list';
    public const MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_LIST = 'block-tagstances-against-scroll-list';
    public const MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_LIST = 'block-singlerelatedstancecontent-scroll-list';
    public const MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST = 'block-singlerelatedstancecontent-pro-scroll-list';
    public const MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST = 'block-singlerelatedstancecontent-against-scroll-list';
    public const MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST = 'block-singlerelatedstancecontent-neutral-scroll-list';
    public const MODULE_BLOCK_AUTHORSTANCES_CAROUSEL = 'block-authorstances-carousel';
    public const MODULE_BLOCK_TAGSTANCES_CAROUSEL = 'block-tagstances-carousel';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_STANCES_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_BLOCK_STANCES_SCROLL_ADDONS],
            [self::class, self::MODULE_BLOCK_STANCES_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_STANCES_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_STANCES_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_STANCES_PRO_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_STANCES_PRO_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_STANCES_PRO_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_STANCES_AGAINST_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_STANCES_AGAINST_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_STANCES_AGAINST_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORSTANCES_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORSTANCES_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTHORSTANCES_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_TAGSTANCES_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_TAGSTANCES_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_TAGSTANCES_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORSTANCES_CAROUSEL],
            [self::class, self::MODULE_BLOCK_TAGSTANCES_CAROUSEL],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_BLOCK_AUTHORSTANCES_CAROUSEL => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_BLOCK_AUTHORSTANCES_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_AUTHORSTANCES_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_AUTHORSTANCES_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
            self::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
            self::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
            self::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
            self::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
            self::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
            self::MODULE_BLOCK_STANCES_AGAINST_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_BLOCK_STANCES_AGAINST_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_BLOCK_STANCES_AGAINST_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
            self::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
            self::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
            self::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
            self::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
            self::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
            self::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
            self::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
            self::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
            self::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
            self::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
            self::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
            self::MODULE_BLOCK_STANCES_PRO_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_BLOCK_STANCES_PRO_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_BLOCK_STANCES_PRO_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_BLOCK_STANCES_SCROLL_ADDONS => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_STANCES_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_STANCES_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_STANCES_SCROLL_NAVIGATOR => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_STANCES_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_BLOCK_TAGSTANCES_CAROUSEL => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_BLOCK_TAGSTANCES_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_TAGSTANCES_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_BLOCK_TAGSTANCES_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_STANCES_SCROLL_NAVIGATOR => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_SCROLL_NAVIGATOR],
            self::MODULE_BLOCK_STANCES_SCROLL_ADDONS => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_SCROLL_ADDONS],
            self::MODULE_BLOCK_STANCES_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_STANCES_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_STANCES_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_SCROLL_LIST],
            self::MODULE_BLOCK_STANCES_PRO_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_STANCES_PRO_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_STANCES_PRO_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_SCROLL_LIST],
            self::MODULE_BLOCK_STANCES_AGAINST_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_STANCES_AGAINST_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_STANCES_AGAINST_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_LIST],
            self::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_LIST],
            self::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST],
            self::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST],
            self::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST],
            self::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST],
            self::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST],
            self::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST],
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST],
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST],
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST],
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORSTANCES_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTHORSTANCES_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTHORSTANCES_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST],
            self::MODULE_BLOCK_TAGSTANCES_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSTANCES_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_TAGSTANCES_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSTANCES_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_TAGSTANCES_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSTANCES_SCROLL_LIST],
            self::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST],
            self::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST],
            self::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_LIST => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORSTANCES_CAROUSEL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL],
            self::MODULE_BLOCK_TAGSTANCES_CAROUSEL => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSTANCES_CAROUSEL],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_STANCES_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_STANCES_SCROLL_LIST:
            case self::MODULE_BLOCK_STANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_STANCES_PRO_SCROLL_LIST:
            case self::MODULE_BLOCK_STANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_STANCES_AGAINST_SCROLL_LIST:
            case self::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_LIST:
            case self::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_LIST:
            case self::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_LIST:
            case self::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_LIST:
            case self::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_LIST:
            case self::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST:
            case self::MODULE_BLOCK_AUTHORSTANCES_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORSTANCES_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORSTANCES_SCROLL_LIST:
            case self::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_LIST:
            case self::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_LIST:
            case self::MODULE_BLOCK_TAGSTANCES_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGSTANCES_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_TAGSTANCES_SCROLL_LIST:
            case self::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_LIST:
            case self::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_LIST:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_LIST:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST:
                // For the quickview we return something different
                if (isset(\PoP\Root\App::getState('target')) && \PoP\Root\App::getState('target') == POP_TARGET_QUICKVIEW) {
                    return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_QUICKVIEWBLOCKPOSTLIST];
                }

                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKPOSTLIST];

            case self::MODULE_BLOCK_AUTHORSTANCES_CAROUSEL:
            case self::MODULE_BLOCK_TAGSTANCES_CAROUSEL:
                return [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    public function getLatestcountSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_STANCES_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_STANCES];

            case self::MODULE_BLOCK_STANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_PRO_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_STANCES_PRO];

            case self::MODULE_BLOCK_STANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_AGAINST_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_STANCES_AGAINST];

            case self::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_STANCES_NEUTRAL];

            case self::MODULE_BLOCK_AUTHORSTANCES_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORSTANCES_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_AUTHOR_STANCES];

            case self::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_AUTHOR_STANCES_PRO];

            case self::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_AUTHOR_STANCES_NEUTRAL];

            case self::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_AUTHOR_STANCES_AGAINST];

            case self::MODULE_BLOCK_TAGSTANCES_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGSTANCES_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_TAG_STANCES];

            case self::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_TAG_STANCES_PRO];

            case self::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_TAG_STANCES_NEUTRAL];

            case self::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_TAG_STANCES_AGAINST];

            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_SINGLE_STANCES];

            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_SINGLE_STANCES_PRO];

            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_SINGLE_STANCES_AGAINST];

            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST:
                return [PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_SINGLE_STANCES_NEUTRAL];
        }

        return parent::getLatestcountSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_AUTHORSTANCES_CAROUSEL:
            case self::MODULE_BLOCK_TAGSTANCES_CAROUSEL:
                // Artificial property added to identify the template when adding module-resources
                $this->appendProp($module, $props, 'class', 'pop-block-carousel block-stances-carousel');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



