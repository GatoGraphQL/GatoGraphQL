<?php

class PoP_SocialNetwork_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_DETAILS = 'block-authorfollowers-scroll-details';
    public final const MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS = 'block-authorfollowingusers-scroll-details';
    public final const MODULE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS = 'block-authorsubscribedtotags-scroll-details';
    public final const MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS = 'block-authorrecommendedposts-scroll-details';
    public final const MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_DETAILS = 'block-tagsubscribers-scroll-details';
    public final const MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS = 'block-singlerecommendedby-scroll-details';
    public final const MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS = 'block-singleupvotedby-scroll-details';
    public final const MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS = 'block-singledownvotedby-scroll-details';
    public final const MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW = 'block-authorrecommendedposts-scroll-simpleview';
    public final const MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_FULLVIEW = 'block-authorfollowers-scroll-fullview';
    public final const MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW = 'block-authorfollowingusers-scroll-fullview';
    public final const MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW = 'block-authorrecommendedposts-scroll-fullview';
    public final const MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_FULLVIEW = 'block-tagsubscribers-scroll-fullview';
    public final const MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW = 'block-singlerecommendedby-scroll-fullview';
    public final const MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW = 'block-singleupvotedby-scroll-fullview';
    public final const MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW = 'block-singledownvotedby-scroll-fullview';
    public final const MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_THUMBNAIL = 'block-authorfollowers-scroll-thumbnail';
    public final const MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL = 'block-authorfollowingusers-scroll-thumbnail';
    public final const MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL = 'block-authorrecommendedposts-scroll-thumbnail';
    public final const MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_THUMBNAIL = 'block-tagsubscribers-scroll-thumbnail';
    public final const MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL = 'block-singlerecommendedby-scroll-thumbnail';
    public final const MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL = 'block-singleupvotedby-scroll-thumbnail';
    public final const MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL = 'block-singledownvotedby-scroll-thumbnail';
    public final const MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_LIST = 'block-authorfollowers-scroll-list';
    public final const MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_LIST = 'block-authorfollowingusers-scroll-list';
    public final const MODULE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST = 'block-authorsubscribedtotags-scroll-list';
    public final const MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST = 'block-authorrecommendedposts-scroll-list';
    public final const MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_LIST = 'block-tagsubscribers-scroll-list';
    public final const MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST = 'block-singlerecommendedby-scroll-list';
    public final const MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST = 'block-singleupvotedby-scroll-list';
    public final const MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST = 'block-singledownvotedby-scroll-list';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST],

            [self::class, self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_LIST],

            [self::class, self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::MODULE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO,
            self::MODULE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO,
            self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS],
            self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS],
            self::MODULE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS],
            self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS],
            self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_FULLVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_THUMBNAIL => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST],
            self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_DETAILS],
            self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_FULLVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_THUMBNAIL => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_LIST],
            self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS],
            self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS],
            self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS],
            self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST],
            self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST],
            self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    protected function getSectionfilterModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST:
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
            case self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKPOSTLIST];

            case self::MODULE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_TAGLIST];

            case self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_LIST:
            case self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_LIST:
            case self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS:
            case self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST:
            case self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS:
            case self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST:
            case self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS:
            case self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST:
            case self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKUSERLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }
}



