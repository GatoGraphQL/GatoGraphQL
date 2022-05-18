<?php

use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_SocialNetwork_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS = 'dataload-authorfollowers-scroll-details';
    public final const MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS = 'dataload-authorfollowingusers-scroll-details';
    public final const MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS = 'dataload-authorsubscribedtotags-scroll-details';
    public final const MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS = 'dataload-authorrecommendedposts-scroll-details';
    public final const MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_DETAILS = 'dataload-tagsubscribers-scroll-details';
    public final const MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS = 'dataload-singlerecommendedby-scroll-details';
    public final const MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS = 'dataload-singleupvotedby-scroll-details';
    public final const MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS = 'dataload-singledownvotedby-scroll-details';
    public final const MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW = 'dataload-authorrecommendedposts-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW = 'dataload-authorfollowers-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW = 'dataload-authorfollowingusers-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW = 'dataload-authorrecommendedposts-scroll-fullview';
    public final const MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_FULLVIEW = 'dataload-tagsubscribers-scroll-fullview';
    public final const MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW = 'dataload-singlerecommendedby-scroll-fullview';
    public final const MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW = 'dataload-singleupvotedby-scroll-fullview';
    public final const MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW = 'dataload-singledownvotedby-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL = 'dataload-authorfollowers-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL = 'dataload-authorfollowingusers-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL = 'dataload-authorrecommendedposts-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_THUMBNAIL = 'dataload-tagsubscribers-scroll-thumbnail';
    public final const MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL = 'dataload-singlerecommendedby-scroll-thumbnail';
    public final const MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL = 'dataload-singleupvotedby-scroll-thumbnail';
    public final const MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL = 'dataload-singledownvotedby-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST = 'dataload-authorfollowers-scroll-list';
    public final const MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST = 'dataload-authorfollowingusers-scroll-list';
    public final const MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST = 'dataload-authorsubscribedtotags-scroll-list';
    public final const MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST = 'dataload-authorrecommendedposts-scroll-list';
    public final const MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_LIST = 'dataload-tagsubscribers-scroll-list';
    public final const MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST = 'dataload-singlerecommendedby-scroll-list';
    public final const MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST = 'dataload-singleupvotedby-scroll-list';
    public final const MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST = 'dataload-singledownvotedby-scroll-list';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
            self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO,
            self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO,
            self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_DETAILS => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_FULLVIEW => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_LIST => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_THUMBNAIL => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_DETAILS],
            self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_DETAILS],
            self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_TAGS_DETAILS],
            self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_DETAILS],
            self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_SIMPLEVIEW],

            self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORCONTENT_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_LIST],
            self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_LIST],
            self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_TAGS_LIST],
            self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_LIST],

            self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_DETAILS],
            self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_FULLVIEW],
            self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_LIST],

            self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_FULLVIEW],
            self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_FULLVIEW],
            self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_FULLVIEW],

            self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_DETAILS],
            self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_DETAILS],
            self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_DETAILS],

            self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_THUMBNAIL],
            self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_THUMBNAIL],
            self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_THUMBNAIL],

            self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_LIST],
            self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_LIST],
            self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_LIST],
        );

        return $inner_modules[$componentVariation[1]] ?? null;
    }

    public function getFilterSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_CONTENT];

            case self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_TAGS];

            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];
        }

        return parent::getFilterSubmodule($componentVariation);
    }

    public function getFormat(array $componentVariation): ?string
    {

        // Add the format attr
        $details = array(
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS],
        );
        $simpleviews = array(
            [self::class, self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST],
        );
        if (in_array($componentVariation, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($componentVariation, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($componentVariation, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        } elseif (in_array($componentVariation, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($componentVariation, $lists)) {
            $format = POP_FORMAT_LIST;
        }

        return $format ?? parent::getFormat($componentVariation);
    }

    // public function getNature(array $componentVariation)
    // {
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST:
    //             return UserRequestNature::USER;

    //         case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_LIST:
    //             return TagRequestNature::TAG;

    //         case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($componentVariation);
    // }

    protected function getMutableonrequestDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagsubscribers($ret);
                break;

            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorfollowers($ret);
                break;

            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorfollowingusers($ret);
                break;

            case self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorsubscribedtotags($ret);
                break;

            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorrecommendedposts($ret);
                break;

            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsRecommendedby($ret);
                break;

            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsUpvotedby($ret);
                break;

            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsDownvotedby($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST:
                return $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);

            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();

            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_LIST:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('tags', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('results', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('users', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('followers', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



