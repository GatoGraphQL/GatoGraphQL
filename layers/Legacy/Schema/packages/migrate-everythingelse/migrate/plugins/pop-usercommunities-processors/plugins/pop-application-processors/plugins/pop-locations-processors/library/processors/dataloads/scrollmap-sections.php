<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_UserCommunities_ModuleProcessor_CustomScrollMapSectionDataloads extends GD_EM_Module_Processor_ScrollMapDataloadsBase
{
    public const MODULE_DATALOAD_COMMUNITIES_SCROLLMAP = 'dataload-communities-scrollmap';
    public const MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP = 'dataload-authormembers-scrollmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP => [PoP_UserCommunities_ModuleProcessor_CustomScrollMapSections::class, PoP_UserCommunities_ModuleProcessor_CustomScrollMapSections::MODULE_SCROLLMAP_COMMUNITIES_SCROLLMAP],
            self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP => [PoP_UserCommunities_ModuleProcessor_CustomScrollMapSections::class, PoP_UserCommunities_ModuleProcessor_CustomScrollMapSections::MODULE_SCROLLMAP_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP:
                return [GD_URE_Module_Processor_CustomFilters::class, GD_URE_Module_Processor_CustomFilters::MODULE_FILTER_COMMUNITIES];

            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORCOMMUNITYMEMBERS];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {
        $maps = array(
            [self::class, self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );
        if (in_array($module, $maps)) {
            $format = POP_FORMAT_MAP;
        }

        return $format ?? parent::getFormat($module);
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
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
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP:
                $ret['role'] = GD_URE_ROLE_COMMUNITY;
                break;
        }

        return $ret;
    }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
         // Members of the Community
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                $vars = ApplicationState::getVars();
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                // If the profile is not a community, then return no users at all (Eg: a community opting out from having members)
                if (gdUreIsCommunity($author)) {
                    URE_CommunityUtils::addDataloadqueryargsCommunitymembers($ret, $author);
                }
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
         // Members of the Community
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                $vars = ApplicationState::getVars();
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                // If the profile is not a community, then return no users at all (Eg: a community opting out from having members)
                if (!gdUreIsCommunity($author)) {
                    $this->setProp($module, $props, 'skip-data-load', true);
                }
                break;
        }

        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('members', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('communities', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}

