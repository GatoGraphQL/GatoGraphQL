<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_UserCommunities_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public const MODULE_DATALOAD_COMMUNITIES_TYPEAHEAD = 'dataload-communities-typeahead';
    public const MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD = 'dataload-authorpluscommunitymembers-typeahead';
    public const MODULE_DATALOAD_COMMUNITIES_SCROLL_DETAILS = 'dataload-communities-scroll-details';
    public const MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS = 'dataload-authormembers-scroll-details';
    public const MODULE_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW = 'dataload-communities-scroll-fullview';
    public const MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW = 'dataload-authormembers-scroll-fullview';
    public const MODULE_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL = 'dataload-communities-scroll-thumbnail';
    public const MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL = 'dataload-authormembers-scroll-thumbnail';
    public const MODULE_DATALOAD_COMMUNITIES_SCROLL_LIST = 'dataload-communities-scroll-list';
    public const MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST = 'dataload-authormembers-scroll-list';
    public const MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL = 'dataload-authormembers-carousel';

    public function getModulesToProcess(): array
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

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
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
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getInnerSubmodule(array $module)
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

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
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

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
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
        if (in_array($module, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($module, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($module, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($module, $lists)) {
            $format = POP_FORMAT_LIST;
        } elseif (in_array($module, $typeaheads)) {
            $format = POP_FORMAT_TYPEAHEAD;
        } elseif (in_array($module, $carousels)) {
            $format = POP_FORMAT_CAROUSEL;
        }

        return $format ?? parent::getFormat($module);
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
    //         case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL:
    //             return UserRouteNatures::USER;
    //     }

    //     return parent::getNature($module);
    // }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        switch ($module[1]) {
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

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        $vars = ApplicationState::getVars();
        switch ($module[1]) {
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

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        $ret = parent::getObjectIDOrIDs($module, $props, $data_properties);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
                // Also include the current author
                $vars = ApplicationState::getVars();
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                array_unshift($ret, $author);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
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

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
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

        parent::initModelProps($module, $props);
    }

    public function initRequestProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
         // Members of the Community
            case self::MODULE_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
                $vars = ApplicationState::getVars();
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                // If the profile is not a community, then return no users at all (Eg: a community opting out from having members)
                if (!gdUreIsCommunity($author)) {
                    $this->setProp($module, $props, 'skip-data-load', true);
                }
                break;
        }

        parent::initRequestProps($module, $props);
    }
}



