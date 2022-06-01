<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads extends GD_EM_Module_Processor_ScrollMapDataloadsBase
{
    public final const COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP = 'dataload-authorfollowers-scrollmap';
    public final const COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP = 'dataload-authorfollowingusers-scrollmap';
    public final const COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP = 'dataload-singlerecommendedby-scrollmap';
    public final const COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP = 'dataload-singleupvotedby-scrollmap';
    public final const COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP = 'dataload-singledownvotedby-scrollmap';
    public final const COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP = 'dataload-tagsubscribers-scrollmap';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP,
            self::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP,
            self::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP,
            self::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP,
            self::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP,
            self::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::COMPONENT_SCROLLMAP_AUTHORFOLLOWERS_SCROLLMAP],
            self::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::COMPONENT_SCROLLMAP_AUTHORFOLLOWINGUSERS_SCROLLMAP],
            self::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::COMPONENT_SCROLLMAP_SINGLERECOMMENDEDBY_SCROLLMAP],
            self::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::COMPONENT_SCROLLMAP_SINGLEUPVOTEDBY_SCROLLMAP],
            self::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::COMPONENT_SCROLLMAP_SINGLEDOWNVOTEDBY_SCROLLMAP],
            self::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections::COMPONENT_SCROLLMAP_TAGSUBSCRIBERS_SCROLLMAP],
        );

        return $inner_components[$component->name] ?? null;
    }

    public function getFilterSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP:
            case self::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP:
            case self::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP:
            case self::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_USERS];
        }

        return parent::getFilterSubcomponent($component);
    }

    public function getFormat(\PoP\ComponentModel\Component\Component $component): ?string
    {
        $maps = array(
            self::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP,
            self::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP,

            self::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP,
            self::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP,
            self::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP,

            self::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP,
        );
        if (in_array($component, $maps)) {
            $format = POP_FORMAT_MAP;
        }

        return $format ?? parent::getFormat($component);
    }

    // public function getNature(\PoP\ComponentModel\Component\Component $component)
    // {
    //     switch ($component->name) {
    //         case self::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP:
    //         case self::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP:
    //             return UserRequestNature::USER;

    //         case self::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP:
    //             return TagRequestNature::TAG;

    //         case self::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP:
    //         case self::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP:
    //         case self::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($component);
    // }

    protected function getMutableonrequestDataloadQueryArgs(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagsubscribers($ret);
                break;

            case self::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorfollowers($ret);
                break;

            case self::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorfollowingusers($ret);
                break;

            case self::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsRecommendedby($ret);
                break;

            case self::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsUpvotedby($ret);
                break;

            case self::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsDownvotedby($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP:
            case self::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP:
            case self::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP:
            case self::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP:
            case self::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP:
            case self::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP:
            case self::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('users', 'poptheme-wassup'));
                break;

            case self::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('followers', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



