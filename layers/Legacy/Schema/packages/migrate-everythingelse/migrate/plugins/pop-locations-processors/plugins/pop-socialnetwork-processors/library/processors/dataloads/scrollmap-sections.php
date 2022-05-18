<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads extends GD_EM_Module_Processor_ScrollMapDataloadsBase
{
    public final const MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP = 'dataload-authorfollowers-scrollmap';
    public final const MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP = 'dataload-authorfollowingusers-scrollmap';
    public final const MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP = 'dataload-singlerecommendedby-scrollmap';
    public final const MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP = 'dataload-singleupvotedby-scrollmap';
    public final const MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP = 'dataload-singledownvotedby-scrollmap';
    public final const MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP = 'dataload-tagsubscribers-scrollmap';

    public function getComponentsToProcess(): array
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

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_AUTHORFOLLOWERS_SCROLLMAP],
            self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_AUTHORFOLLOWINGUSERS_SCROLLMAP],
            self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_SINGLERECOMMENDEDBY_SCROLLMAP],
            self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_SINGLEUPVOTEDBY_SCROLLMAP],
            self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_SINGLEDOWNVOTEDBY_SCROLLMAP],
            self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_TAGSUBSCRIBERS_SCROLLMAP],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getFilterSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];
        }

        return parent::getFilterSubmodule($component);
    }

    public function getFormat(array $component): ?string
    {
        $maps = array(
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP],

            [self::class, self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP],

            [self::class, self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP],
        );
        if (in_array($component, $maps)) {
            $format = POP_FORMAT_MAP;
        }

        return $format ?? parent::getFormat($component);
    }

    // public function getNature(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP:
    //         case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP:
    //             return UserRequestNature::USER;

    //         case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP:
    //             return TagRequestNature::TAG;

    //         case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP:
    //         case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP:
    //         case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($component);
    // }

    protected function getMutableonrequestDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component[1]) {
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

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP:
            case self::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
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

        parent::initModelProps($component, $props);
    }
}



