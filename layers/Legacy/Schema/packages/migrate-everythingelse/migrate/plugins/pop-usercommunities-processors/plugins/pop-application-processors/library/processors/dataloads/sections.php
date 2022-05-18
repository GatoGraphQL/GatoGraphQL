<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_UserCommunities_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const MODULE_DATALOAD_COMMUNITIES_TYPEAHEAD = 'dataload-communities-typeahead';
    public final const MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD = 'dataload-authorpluscommunitymembers-typeahead';
    public final const MODULE_DATALOAD_COMMUNITIES_SCROLL_DETAILS = 'dataload-communities-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS = 'dataload-authormembers-scroll-details';
    public final const MODULE_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW = 'dataload-communities-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW = 'dataload-authormembers-scroll-fullview';
    public final const MODULE_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL = 'dataload-communities-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL = 'dataload-authormembers-scroll-thumbnail';
    public final const MODULE_DATALOAD_COMMUNITIES_SCROLL_LIST = 'dataload-communities-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST = 'dataload-authormembers-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL = 'dataload-authormembers-carousel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_COMMUNITIES_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_COMMUNITIES_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_COMMUNITIES_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD => POP_USERCOMMUNITIES_ROUTE_COMMUNITYPLUSMEMBERS,
            self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::MODULE_DATALOAD_COMMUNITIES_SCROLL_DETAILS => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            self::MODULE_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            self::MODULE_DATALOAD_COMMUNITIES_SCROLL_LIST => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            self::MODULE_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            self::MODULE_DATALOAD_COMMUNITIES_TYPEAHEAD => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_COMMUNITIES_TYPEAHEAD => [PoP_Module_Processor_UserTypeaheadComponentLayouts::class, PoP_Module_Processor_UserTypeaheadComponentLayouts::MODULE_LAYOUTUSER_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD => [PoP_Module_Processor_UserTypeaheadComponentLayouts::class, PoP_Module_Processor_UserTypeaheadComponentLayouts::MODULE_LAYOUTUSER_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_COMMUNITIES_SCROLL_DETAILS => [PoP_UserCommunities_Module_Processor_CustomScrolls::class, PoP_UserCommunities_Module_Processor_CustomScrolls::MODULE_SCROLL_COMMUNITIES_DETAILS],
            self::MODULE_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW => [PoP_UserCommunities_Module_Processor_CustomScrolls::class, PoP_UserCommunities_Module_Processor_CustomScrolls::MODULE_SCROLL_COMMUNITIES_FULLVIEW],
            self::MODULE_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL => [PoP_UserCommunities_Module_Processor_CustomScrolls::class, PoP_UserCommunities_Module_Processor_CustomScrolls::MODULE_SCROLL_COMMUNITIES_THUMBNAIL],
            self::MODULE_DATALOAD_COMMUNITIES_SCROLL_LIST => [PoP_UserCommunities_Module_Processor_CustomScrolls::class, PoP_UserCommunities_Module_Processor_CustomScrolls::MODULE_SCROLL_COMMUNITIES_LIST],
            self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_LIST],
            self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL => [PoP_UserCommunities_Module_Processor_CustomCarousels::class, PoP_UserCommunities_Module_Processor_CustomCarousels::MODULE_CAROUSEL_AUTHORMEMBERS],
        );

        return $inner_modules[$componentVariation[1]] ?? null;
    }

    public function getFilterSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_COMMUNITIES_TYPEAHEAD:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_LIST:
                return [GD_URE_Module_Processor_CustomFilters::class, GD_URE_Module_Processor_CustomFilters::MODULE_FILTER_COMMUNITIES];

            case self::MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];

            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORCOMMUNITYMEMBERS];
        }

        return parent::getFilterSubmodule($componentVariation);
    }

    public function getFormat(array $componentVariation): ?string
    {

        // Add the format attr
        $details = array(
            [self::class, self::MODULE_DATALOAD_COMMUNITIES_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::MODULE_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_COMMUNITIES_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST],
        );
        $typeaheads = array(
            [self::class, self::MODULE_DATALOAD_COMMUNITIES_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD],
        );
        $carousels = array(
            [self::class, self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL],
        );
        if (in_array($componentVariation, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($componentVariation, $fullviews)) {
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
    //         case self::MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
    //         case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL:
    //             return UserRequestNature::USER;
    //     }

    //     return parent::getNature($componentVariation);
    // }

    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL:
                $ret['orderby'] = NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:users:registrationdate');
                $ret['order'] = 'DESC';
                break;
            case self::MODULE_DATALOAD_COMMUNITIES_TYPEAHEAD:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_LIST:
                $ret['role'] = GD_URE_ROLE_COMMUNITY;
                break;
        }

        return $ret;
    }

    protected function getMutableonrequestDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($componentVariation, $props);

        switch ($componentVariation[1]) {
             // Members of the Community
            case self::MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                // If the profile is not a community, then return no users at all (Eg: a Community opting out from having members)
                if (gdUreIsCommunity($author)) {
                    URE_CommunityUtils::addDataloadqueryargsCommunitymembers($ret, $author);
                }
                break;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(array $componentVariation, array &$props, &$data_properties): string | int | array
    {
        $ret = parent::getObjectIDOrIDs($componentVariation, $props, $data_properties);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
                // Also include the current author
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                array_unshift($ret, $author);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_COMMUNITIES_TYPEAHEAD:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('members', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('communities', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function initRequestProps(array $componentVariation, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
         // Members of the Community
            case self::MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                // If the profile is not a community, then return no users at all (Eg: a community opting out from having members)
                if (!gdUreIsCommunity($author)) {
                    $this->setProp($componentVariation, $props, 'skip-data-load', true);
                }
                break;
        }

        parent::initRequestProps($componentVariation, $props);
    }
}



