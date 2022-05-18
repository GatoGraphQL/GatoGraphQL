<?php

use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPSchema\Stances\TypeResolvers\ObjectType\StanceObjectTypeResolver;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class UserStance_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const MODULE_DATALOAD_STANCES_TYPEAHEAD = 'dataload-stances-typeahead';
    public final const MODULE_DATALOAD_STANCES_SCROLL_NAVIGATOR = 'dataload-stances-scroll-navigator';
    public final const MODULE_DATALOAD_STANCES_SCROLL_ADDONS = 'dataload-stances-scroll-addons';
    public final const MODULE_DATALOAD_STANCES_SCROLL_FULLVIEW = 'dataload-stances-scroll-fullview';
    public final const MODULE_DATALOAD_STANCES_PRO_SCROLL_FULLVIEW = 'dataload-stances-pro-scroll-fullview';
    public final const MODULE_DATALOAD_STANCES_AGAINST_SCROLL_FULLVIEW = 'dataload-stances-against-scroll-fullview';
    public final const MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_FULLVIEW = 'dataload-stances-neutral-scroll-fullview';
    public final const MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_FULLVIEW = 'dataload-stances-pro-general-scroll-fullview';
    public final const MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW = 'dataload-stances-against-general-scroll-fullview';
    public final const MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW = 'dataload-stances-neutral-general-scroll-fullview';
    public final const MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW = 'dataload-stances-pro-article-scroll-fullview';
    public final const MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW = 'dataload-stances-against-article-scroll-fullview';
    public final const MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW = 'dataload-stances-neutral-article-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW = 'dataload-authorstances-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW = 'dataload-authorstances-pro-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW = 'dataload-authorstances-neutral-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW = 'dataload-authorstances-against-scroll-fullview';
    public final const MODULE_DATALOAD_TAGSTANCES_SCROLL_FULLVIEW = 'dataload-tagstances-scroll-fullview';
    public final const MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW = 'dataload-tagstances-pro-scroll-fullview';
    public final const MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW = 'dataload-tagstances-neutral-scroll-fullview';
    public final const MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW = 'dataload-tagstances-against-scroll-fullview';
    public final const MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW = 'dataload-singlerelatedstancecontent-scroll-fullview';
    public final const MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW = 'dataload-singlerelatedstancecontent-pro-scroll-fullview';
    public final const MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW = 'dataload-singlerelatedstancecontent-against-scroll-fullview';
    public final const MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW = 'dataload-singlerelatedstancecontent-neutral-scroll-fullview';
    public final const MODULE_DATALOAD_STANCES_SCROLL_THUMBNAIL = 'dataload-stances-scroll-thumbnail';
    public final const MODULE_DATALOAD_STANCES_PRO_SCROLL_THUMBNAIL = 'dataload-stances-pro-scroll-thumbnail';
    public final const MODULE_DATALOAD_STANCES_AGAINST_SCROLL_THUMBNAIL = 'dataload-stances-against-scroll-thumbnail';
    public final const MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_THUMBNAIL = 'dataload-stances-neutral-scroll-thumbnail';
    public final const MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL = 'dataload-stances-pro-general-scroll-thumbnail';
    public final const MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL = 'dataload-stances-against-general-scroll-thumbnail';
    public final const MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL = 'dataload-stances-neutral-general-scroll-thumbnail';
    public final const MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL = 'dataload-stances-pro-article-scroll-thumbnail';
    public final const MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL = 'dataload-stances-against-article-scroll-thumbnail';
    public final const MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL = 'dataload-stances-neutral-article-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL = 'dataload-authorstances-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL = 'dataload-authorstances-pro-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL = 'dataload-authorstances-neutral-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL = 'dataload-authorstances-against-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGSTANCES_SCROLL_THUMBNAIL = 'dataload-tagstances-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL = 'dataload-tagstances-pro-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL = 'dataload-tagstances-neutral-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL = 'dataload-tagstances-against-scroll-thumbnail';
    public final const MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL = 'dataload-singlerelatedstancecontent-scroll-thumbnail';
    public final const MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL = 'dataload-singlerelatedstancecontent-pro-scroll-thumbnail';
    public final const MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL = 'dataload-singlerelatedstancecontent-against-scroll-thumbnail';
    public final const MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL = 'dataload-singlerelatedstancecontent-neutral-scroll-thumbnail';
    public final const MODULE_DATALOAD_STANCES_SCROLL_LIST = 'dataload-stances-scroll-list';
    public final const MODULE_DATALOAD_STANCES_PRO_SCROLL_LIST = 'dataload-stances-pro-scroll-list';
    public final const MODULE_DATALOAD_STANCES_AGAINST_SCROLL_LIST = 'dataload-stances-against-scroll-list';
    public final const MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_LIST = 'dataload-stances-neutral-scroll-list';
    public final const MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST = 'dataload-stances-pro-general-scroll-list';
    public final const MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST = 'dataload-stances-against-general-scroll-list';
    public final const MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST = 'dataload-stances-neutral-general-scroll-list';
    public final const MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST = 'dataload-stances-pro-article-scroll-list';
    public final const MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST = 'dataload-stances-against-article-scroll-list';
    public final const MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST = 'dataload-stances-neutral-article-scroll-list';
    public final const MODULE_DATALOAD_AUTHORSTANCES_SCROLL_LIST = 'dataload-authorstances-scroll-list';
    public final const MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST = 'dataload-authorstances-pro-scroll-list';
    public final const MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST = 'dataload-authorstances-neutral-scroll-list';
    public final const MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST = 'dataload-authorstances-against-scroll-list';
    public final const MODULE_DATALOAD_TAGSTANCES_SCROLL_LIST = 'dataload-tagstances-scroll-list';
    public final const MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST = 'dataload-tagstances-pro-scroll-list';
    public final const MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST = 'dataload-tagstances-neutral-scroll-list';
    public final const MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST = 'dataload-tagstances-against-scroll-list';
    public final const MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST = 'dataload-singlerelatedstancecontent-scroll-list';
    public final const MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST = 'dataload-singlerelatedstancecontent-pro-scroll-list';
    public final const MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST = 'dataload-singlerelatedstancecontent-against-scroll-list';
    public final const MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST = 'dataload-singlerelatedstancecontent-neutral-scroll-list';
    public final const MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL = 'dataload-authorstances-carousel';
    public final const MODULE_DATALOAD_TAGSTANCES_CAROUSEL = 'dataload-tagstances-carousel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_STANCES_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_STANCES_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_STANCES_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_STANCES_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_CAROUSEL],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
            self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
            self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
            self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
            self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
            self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
            self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
            self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
            self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
            self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
            self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
            self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
            self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
            self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
            self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
            self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
            self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
            self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
            self::MODULE_DATALOAD_STANCES_PRO_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_DATALOAD_STANCES_PRO_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_DATALOAD_STANCES_PRO_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_DATALOAD_STANCES_SCROLL_ADDONS => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_STANCES_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_STANCES_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_STANCES_SCROLL_NAVIGATOR => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_STANCES_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_STANCES_TYPEAHEAD => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            self::MODULE_DATALOAD_TAGSTANCES_CAROUSEL => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_DATALOAD_TAGSTANCES_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_TAGSTANCES_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES,
            self::MODULE_DATALOAD_TAGSTANCES_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_componentVariations = array(

            /*********************************************
             * Typeaheads
             *********************************************/
            // Straight to the layout
            self::MODULE_DATALOAD_STANCES_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Common blocks (Home/Page/Author/Single)
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::MODULE_DATALOAD_STANCES_SCROLL_NAVIGATOR => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_NAVIGATOR],
            self::MODULE_DATALOAD_STANCES_SCROLL_ADDONS => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_ADDONS],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Home/Page blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::MODULE_DATALOAD_STANCES_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_STANCES_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_STANCES_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],

            self::MODULE_DATALOAD_STANCES_PRO_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_STANCES_PRO_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_STANCES_PRO_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],

            self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],

            self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],

            self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],

            self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],

            self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],

            self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],

            self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],

            self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Single blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_SINGLERELATEDSTANCECONTENT_FULLVIEW],
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_SINGLERELATEDSTANCECONTENT_FULLVIEW],
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_SINGLERELATEDSTANCECONTENT_FULLVIEW],
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_SINGLERELATEDSTANCECONTENT_FULLVIEW],
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Author blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORSTANCES_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORSTANCES_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORSTANCES_LIST],
            self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORSTANCES_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORSTANCES_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORSTANCES_LIST],
            self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORSTANCES_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORSTANCES_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORSTANCES_LIST],
            self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORSTANCES_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORSTANCES_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORSTANCES_LIST],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Tag blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::MODULE_DATALOAD_TAGSTANCES_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_TAGSTANCES_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_TAGSTANCES_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],
            self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],
            self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],
            self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],

            /*********************************************
            * Post Carousels
            *********************************************/

            self::MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL => [UserStance_Module_Processor_CustomCarousels::class, UserStance_Module_Processor_CustomCarousels::MODULE_CAROUSEL_AUTHORSTANCES],
            self::MODULE_DATALOAD_TAGSTANCES_CAROUSEL => [UserStance_Module_Processor_CustomCarousels::class, UserStance_Module_Processor_CustomCarousels::MODULE_CAROUSEL_TAGSTANCES],
        );

        return $inner_componentVariations[$componentVariation[1]] ?? null;
    }

    public function getFilterSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_STANCES_TYPEAHEAD:
            case self::MODULE_DATALOAD_STANCES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_STANCES];

            case self::MODULE_DATALOAD_STANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_STANCES_STANCE];

            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_STANCES_GENERALSTANCE];

            case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_LIST:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORSTANCES];

            case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_LIST:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_STANCES];

            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORSTANCES_STANCE];

            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_STANCES_STANCE];
        }

        return parent::getFilterSubmodule($componentVariation);
    }

    public function getFormat(array $componentVariation): ?string
    {
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_STANCES_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::MODULE_DATALOAD_STANCES_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_STANCES_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST],
        );
        $typeaheads = array(
            [self::class, self::MODULE_DATALOAD_STANCES_TYPEAHEAD],
        );
        $carousels = array(
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGSTANCES_CAROUSEL],
        );
        if (in_array($componentVariation, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($componentVariation, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($componentVariation, $lists)) {
            $format = POP_FORMAT_LIST;
        } elseif (in_array($componentVariation, $typeaheads)) {
            $format = POP_FORMAT_TYPEAHEAD;
        } elseif (in_array($componentVariation, $carousels)) {
            $format = POP_FORMAT_CAROUSEL;
        }

        return $format ?? parent::getFormat($componentVariation);
    }

    // public function getNature(array $componentVariation)
    // {
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL:
    //             return UserRequestNature::USER;

    //         case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGSTANCES_CAROUSEL:
    //             return TagRequestNature::TAG;

    //         case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($componentVariation);
    // }

    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_STANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST:
                $ret['tax-query'][] = [
                  'taxonomy' => POP_USERSTANCE_TAXONOMY_STANCE,
                  'terms'    => POP_USERSTANCE_TERM_STANCE_PRO,
                ];
                break;

            case self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST:
                $ret['tax-query'][] = [
                  'taxonomy' => POP_USERSTANCE_TAXONOMY_STANCE,
                  'terms'    => POP_USERSTANCE_TERM_STANCE_AGAINST,
                ];
                break;

            case self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST:
                $ret['tax-query'][] = [
                    'taxonomy' => POP_USERSTANCE_TAXONOMY_STANCE,
                    'terms'    => POP_USERSTANCE_TERM_STANCE_NEUTRAL,
                ];
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST:
                $ret['meta-query'][] = [
                    'key' => \PoPCMSSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_STANCETARGET),
                    'compare' => 'NOT EXISTS'
                ];
                break;

            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST:
                $ret['meta-query'][] = [
                    'key' => \PoPCMSSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_STANCETARGET),
                    'compare' => 'EXISTS'
                ];
                break;

         // Filter by Related Highlights
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST:
                // If pro/against/neutral, replace the category
                $cats = array(
                    self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW => POP_USERSTANCE_TERM_STANCE_PRO,
                    self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL => POP_USERSTANCE_TERM_STANCE_PRO,
                    self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST => POP_USERSTANCE_TERM_STANCE_PRO,
                    self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW => POP_USERSTANCE_TERM_STANCE_AGAINST,
                    self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL => POP_USERSTANCE_TERM_STANCE_AGAINST,
                    self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST => POP_USERSTANCE_TERM_STANCE_AGAINST,
                    self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW => POP_USERSTANCE_TERM_STANCE_NEUTRAL,
                    self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL => POP_USERSTANCE_TERM_STANCE_NEUTRAL,
                    self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST => POP_USERSTANCE_TERM_STANCE_NEUTRAL,
                );
                if ($cat = $cats[$componentVariation[1]] ?? null) {
                    $ret['tax-query'][] = [
                        'taxonomy' => POP_USERSTANCE_TAXONOMY_STANCE,
                        'terms'    => $cat,
                    ];
                }
                break;
        }

        switch ($componentVariation[1]) {
         // Order the Author Thoughts Carousel, so that it always shows the General thought first, and the then article-related ones
            case self::MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL:
                // General thought: menu_order = 0. Article-related thought: menu_order = 1. So order ASC.
                // Moved to WordPress-specific code
                // $ret['orderby'] = array('menu_order' => 'ASC', 'date' => 'DESC');
                $ret['orderby'] = [
                    'date' => 'DESC',
                ];
                break;
        }

        return $ret;
    }

    protected function getMutableonrequestDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($componentVariation, $props);

        switch ($componentVariation[1]) {
         // Filter by the Profile/Community
            case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorcontent($ret);
                break;

            case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_CAROUSEL:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagcontent($ret);
                break;

         // Filter by Related Highlights
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST:
                UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsSinglestances($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_STANCES_TYPEAHEAD:
            case self::MODULE_DATALOAD_STANCES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_STANCES_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_STANCES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL:
            case self::MODULE_DATALOAD_TAGSTANCES_CAROUSEL:
                return $this->instanceManager->getInstance(StanceObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_STANCES_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_STANCES_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_STANCES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL:
            case self::MODULE_DATALOAD_TAGSTANCES_CAROUSEL:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', PoP_UserStance_PostNameUtils::getNamesLc());
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



