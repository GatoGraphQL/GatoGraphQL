<?php

use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;

class PoP_SocialNetwork_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public const MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS = 'dataload-authorfollowers-scroll-details';
    public const MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS = 'dataload-authorfollowingusers-scroll-details';
    public const MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS = 'dataload-authorsubscribedtotags-scroll-details';
    public const MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS = 'dataload-authorrecommendedposts-scroll-details';
    public const MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_DETAILS = 'dataload-tagsubscribers-scroll-details';
    public const MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS = 'dataload-singlerecommendedby-scroll-details';
    public const MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS = 'dataload-singleupvotedby-scroll-details';
    public const MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS = 'dataload-singledownvotedby-scroll-details';
    public const MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW = 'dataload-authorrecommendedposts-scroll-simpleview';
    public const MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW = 'dataload-authorfollowers-scroll-fullview';
    public const MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW = 'dataload-authorfollowingusers-scroll-fullview';
    public const MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW = 'dataload-authorrecommendedposts-scroll-fullview';
    public const MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_FULLVIEW = 'dataload-tagsubscribers-scroll-fullview';
    public const MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW = 'dataload-singlerecommendedby-scroll-fullview';
    public const MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW = 'dataload-singleupvotedby-scroll-fullview';
    public const MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW = 'dataload-singledownvotedby-scroll-fullview';
    public const MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL = 'dataload-authorfollowers-scroll-thumbnail';
    public const MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL = 'dataload-authorfollowingusers-scroll-thumbnail';
    public const MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL = 'dataload-authorrecommendedposts-scroll-thumbnail';
    public const MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_THUMBNAIL = 'dataload-tagsubscribers-scroll-thumbnail';
    public const MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL = 'dataload-singlerecommendedby-scroll-thumbnail';
    public const MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL = 'dataload-singleupvotedby-scroll-thumbnail';
    public const MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL = 'dataload-singledownvotedby-scroll-thumbnail';
    public const MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST = 'dataload-authorfollowers-scroll-list';
    public const MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST = 'dataload-authorfollowingusers-scroll-list';
    public const MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST = 'dataload-authorsubscribedtotags-scroll-list';
    public const MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST = 'dataload-authorrecommendedposts-scroll-list';
    public const MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_LIST = 'dataload-tagsubscribers-scroll-list';
    public const MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST = 'dataload-singlerecommendedby-scroll-list';
    public const MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST = 'dataload-singleupvotedby-scroll-list';
    public const MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST = 'dataload-singledownvotedby-scroll-list';

    public function getModulesToProcess(): array
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

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
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
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getInnerSubmodule(array $module)
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

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
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

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
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
        if (in_array($module, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($module, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($module, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        } elseif (in_array($module, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($module, $lists)) {
            $format = POP_FORMAT_LIST;
        }

        return $format ?? parent::getFormat($module);
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
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
    //             return UserRouteNatures::USER;

    //         case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLL_LIST:
    //             return TagRouteNatures::TAG;

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
    //             return CustomPostRouteNatures::CUSTOMPOST;
    //     }

    //     return parent::getNature($module);
    // }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
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

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST:
                return PostTagTypeResolver::class;

            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetTypeResolverClass(CustomPostUnionTypeResolver::class);

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
                return UserTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
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

        parent::initModelProps($module, $props);
    }
}



