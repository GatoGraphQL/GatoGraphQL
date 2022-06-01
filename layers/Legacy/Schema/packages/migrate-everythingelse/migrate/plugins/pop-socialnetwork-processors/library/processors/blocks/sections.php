<?php

class PoP_SocialNetwork_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_DETAILS = 'block-authorfollowers-scroll-details';
    public final const COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS = 'block-authorfollowingusers-scroll-details';
    public final const COMPONENT_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS = 'block-authorsubscribedtotags-scroll-details';
    public final const COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS = 'block-authorrecommendedposts-scroll-details';
    public final const COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_DETAILS = 'block-tagsubscribers-scroll-details';
    public final const COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS = 'block-singlerecommendedby-scroll-details';
    public final const COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS = 'block-singleupvotedby-scroll-details';
    public final const COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS = 'block-singledownvotedby-scroll-details';
    public final const COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW = 'block-authorrecommendedposts-scroll-simpleview';
    public final const COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_FULLVIEW = 'block-authorfollowers-scroll-fullview';
    public final const COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW = 'block-authorfollowingusers-scroll-fullview';
    public final const COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW = 'block-authorrecommendedposts-scroll-fullview';
    public final const COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_FULLVIEW = 'block-tagsubscribers-scroll-fullview';
    public final const COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW = 'block-singlerecommendedby-scroll-fullview';
    public final const COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW = 'block-singleupvotedby-scroll-fullview';
    public final const COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW = 'block-singledownvotedby-scroll-fullview';
    public final const COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_THUMBNAIL = 'block-authorfollowers-scroll-thumbnail';
    public final const COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL = 'block-authorfollowingusers-scroll-thumbnail';
    public final const COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL = 'block-authorrecommendedposts-scroll-thumbnail';
    public final const COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_THUMBNAIL = 'block-tagsubscribers-scroll-thumbnail';
    public final const COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL = 'block-singlerecommendedby-scroll-thumbnail';
    public final const COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL = 'block-singleupvotedby-scroll-thumbnail';
    public final const COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL = 'block-singledownvotedby-scroll-thumbnail';
    public final const COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_LIST = 'block-authorfollowers-scroll-list';
    public final const COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_LIST = 'block-authorfollowingusers-scroll-list';
    public final const COMPONENT_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST = 'block-authorsubscribedtotags-scroll-list';
    public final const COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST = 'block-authorrecommendedposts-scroll-list';
    public final const COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_LIST = 'block-tagsubscribers-scroll-list';
    public final const COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST = 'block-singlerecommendedby-scroll-list';
    public final const COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST = 'block-singleupvotedby-scroll-list';
    public final const COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST = 'block-singledownvotedby-scroll-list';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_DETAILS,
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS,
            self::COMPONENT_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS,
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS,
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW,
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_LIST,
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_LIST,
            self::COMPONENT_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST,
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST,

            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_DETAILS,
            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_LIST,

            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS,
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS,
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS,
            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST,
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST,
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::COMPONENT_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO,
            self::COMPONENT_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO,
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW],
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_FULLVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_THUMBNAIL => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST],
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST],
            self::COMPONENT_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST],
            self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST],
            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_FULLVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_THUMBNAIL => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLL_LIST],
            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST],
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST],
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST],
        );

        return $inner_components[$component->name] ?? null;
    }

    protected function getSectionFilterComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST:
                if (defined('POP_TAXONOMYQUERY_INITIALIZED') && PoP_Application_TaxonomyQuery_ConfigurationUtils::enableFilterAllcontentByTaxonomy() && PoP_ApplicationProcessors_Utils::addSections()) {
                    return [PoP_Module_Processor_InstantaneousFilters::class, PoP_Module_Processor_InstantaneousFilters::COMPONENT_INSTANTANEOUSFILTER_CONTENTSECTIONS];
                }
                break;
        }

        return parent::getSectionFilterComponent($component);
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKPOSTLIST];

            case self::COMPONENT_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_TAGLIST];

            case self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_LIST:
            case self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_LIST:
            case self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST:
            case self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST:
            case self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST:
            case self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKUSERLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }
}



