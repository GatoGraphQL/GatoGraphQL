<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads extends GD_EM_Module_Processor_ScrollMapDataloadsBase
{
    public const MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP = 'dataload-authorfollowers-scrollmap';
    public const MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP = 'dataload-authorfollowingusers-scrollmap';
    public const MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP = 'dataload-singlerecommendedby-scrollmap';
    public const MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP = 'dataload-singleupvotedby-scrollmap';
    public const MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP = 'dataload-singledownvotedby-scrollmap';
    public const MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP = 'dataload-tagsubscribers-scrollmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_AUTHORFOLLOWERS_SCROLLMAP],
            self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_AUTHORFOLLOWINGUSERS_SCROLLMAP],
            self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_SINGLERECOMMENDEDBY_SCROLLMAP],
            self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_SINGLEUPVOTEDBY_SCROLLMAP],
            self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_SINGLEDOWNVOTEDBY_SCROLLMAP],
            self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_TAGSUBSCRIBERS_SCROLLMAP],
        );

        return $inner_modules[$module[1]];
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {
        $maps = array(
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP],

            [self::class, self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP],

            [self::class, self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP],
        );
        if (in_array($module, $maps)) {
            $format = POP_FORMAT_MAP;
        }

        return $format ?? parent::getFormat($module);
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP:
    //         case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP:
    //             return UserRouteNatures::USER;

    //         case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP:
    //             return TagRouteNatures::TAG;

    //         case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP:
    //         case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP:
    //         case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP:
    //             return CustomPostRouteNatures::CUSTOMPOST;
    //     }

    //     return parent::getNature($module);
    // }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagsubscribers($ret);
                break;

            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorfollowers($ret);
                break;

            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorfollowingusers($ret);
                break;

            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsRecommendedby($ret);
                break;

            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsUpvotedby($ret);
                break;

            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsDownvotedby($ret);
                break;
        }

        return $ret;
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP:
                return UserTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('users', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('followers', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}



