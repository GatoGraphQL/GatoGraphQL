<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_UserCommunities_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const COMPONENT_DATALOAD_COMMUNITIES_TYPEAHEAD = 'dataload-communities-typeahead';
    public final const COMPONENT_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD = 'dataload-authorpluscommunitymembers-typeahead';
    public final const COMPONENT_DATALOAD_COMMUNITIES_SCROLL_DETAILS = 'dataload-communities-scroll-details';
    public final const COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS = 'dataload-authormembers-scroll-details';
    public final const COMPONENT_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW = 'dataload-communities-scroll-fullview';
    public final const COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW = 'dataload-authormembers-scroll-fullview';
    public final const COMPONENT_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL = 'dataload-communities-scroll-thumbnail';
    public final const COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL = 'dataload-authormembers-scroll-thumbnail';
    public final const COMPONENT_DATALOAD_COMMUNITIES_SCROLL_LIST = 'dataload-communities-scroll-list';
    public final const COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST = 'dataload-authormembers-scroll-list';
    public final const COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL = 'dataload-authormembers-carousel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_COMMUNITIES_TYPEAHEAD],
            [self::class, self::COMPONENT_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD],
            [self::class, self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD => POP_USERCOMMUNITIES_ROUTE_COMMUNITYPLUSMEMBERS,
            self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_DETAILS => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_LIST => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            self::COMPONENT_DATALOAD_COMMUNITIES_TYPEAHEAD => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponent(array $component)
    {
        $inner_components = array(
            self::COMPONENT_DATALOAD_COMMUNITIES_TYPEAHEAD => [PoP_Module_Processor_UserTypeaheadComponentLayouts::class, PoP_Module_Processor_UserTypeaheadComponentLayouts::COMPONENT_LAYOUTUSER_TYPEAHEAD_COMPONENT],
            self::COMPONENT_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD => [PoP_Module_Processor_UserTypeaheadComponentLayouts::class, PoP_Module_Processor_UserTypeaheadComponentLayouts::COMPONENT_LAYOUTUSER_TYPEAHEAD_COMPONENT],
            self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_DETAILS => [PoP_UserCommunities_Module_Processor_CustomScrolls::class, PoP_UserCommunities_Module_Processor_CustomScrolls::COMPONENT_SCROLL_COMMUNITIES_DETAILS],
            self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW => [PoP_UserCommunities_Module_Processor_CustomScrolls::class, PoP_UserCommunities_Module_Processor_CustomScrolls::COMPONENT_SCROLL_COMMUNITIES_FULLVIEW],
            self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL => [PoP_UserCommunities_Module_Processor_CustomScrolls::class, PoP_UserCommunities_Module_Processor_CustomScrolls::COMPONENT_SCROLL_COMMUNITIES_THUMBNAIL],
            self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_LIST => [PoP_UserCommunities_Module_Processor_CustomScrolls::class, PoP_UserCommunities_Module_Processor_CustomScrolls::COMPONENT_SCROLL_COMMUNITIES_LIST],
            self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_USERS_DETAILS],
            self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_USERS_FULLVIEW],
            self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_USERS_THUMBNAIL],
            self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_USERS_LIST],
            self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL => [PoP_UserCommunities_Module_Processor_CustomCarousels::class, PoP_UserCommunities_Module_Processor_CustomCarousels::COMPONENT_CAROUSEL_AUTHORMEMBERS],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getFilterSubcomponent(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_COMMUNITIES_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_LIST:
                return [GD_URE_Module_Processor_CustomFilters::class, GD_URE_Module_Processor_CustomFilters::COMPONENT_FILTER_COMMUNITIES];

            case self::COMPONENT_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_USERS];

            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORCOMMUNITYMEMBERS];
        }

        return parent::getFilterSubcomponent($component);
    }

    public function getFormat(array $component): ?string
    {

        // Add the format attr
        $details = array(
            [self::class, self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST],
        );
        $typeaheads = array(
            [self::class, self::COMPONENT_DATALOAD_COMMUNITIES_TYPEAHEAD],
            [self::class, self::COMPONENT_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD],
        );
        $carousels = array(
            [self::class, self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL],
        );
        if (in_array($component, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($component, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($component, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($component, $lists)) {
            $format = POP_FORMAT_LIST;
        } elseif (in_array($component, $typeaheads)) {
            $format = POP_FORMAT_TYPEAHEAD;
        } elseif (in_array($component, $carousels)) {
            $format = POP_FORMAT_CAROUSEL;
        }

        return $format ?? parent::getFormat($component);
    }

    // public function getNature(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
    //         case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
    //         case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
    //         case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
    //         case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
    //         case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL:
    //             return UserRequestNature::USER;
    //     }

    //     return parent::getNature($component);
    // }

    protected function getImmutableDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL:
                $ret['orderby'] = NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:users:registrationdate');
                $ret['order'] = 'DESC';
                break;
            case self::COMPONENT_DATALOAD_COMMUNITIES_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_LIST:
                $ret['role'] = GD_URE_ROLE_COMMUNITY;
                break;
        }

        return $ret;
    }

    protected function getMutableonrequestDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component[1]) {
             // Members of the Community
            case self::COMPONENT_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                // If the profile is not a community, then return no users at all (Eg: a Community opting out from having members)
                if (gdUreIsCommunity($author)) {
                    URE_CommunityUtils::addDataloadqueryargsCommunitymembers($ret, $author);
                }
                break;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        $ret = parent::getObjectIDOrIDs($component, $props, $data_properties);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
                // Also include the current author
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                array_unshift($ret, $author);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_COMMUNITIES_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL:
            case self::COMPONENT_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('members', 'poptheme-wassup'));
                break;

            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('communities', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function initRequestProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
         // Members of the Community
            case self::COMPONENT_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                // If the profile is not a community, then return no users at all (Eg: a community opting out from having members)
                if (!gdUreIsCommunity($author)) {
                    $this->setProp($component, $props, 'skip-data-load', true);
                }
                break;
        }

        parent::initRequestProps($component, $props);
    }
}



