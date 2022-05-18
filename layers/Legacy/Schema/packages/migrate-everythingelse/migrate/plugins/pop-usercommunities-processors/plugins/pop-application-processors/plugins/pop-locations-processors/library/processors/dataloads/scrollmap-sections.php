<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads extends GD_EM_Module_Processor_ScrollMapDataloadsBase
{
    public final const MODULE_DATALOAD_COMMUNITIES_SCROLLMAP = 'dataload-communities-scrollmap';
    public final const MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP = 'dataload-authormembers-scrollmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP => [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSections::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSections::MODULE_SCROLLMAP_COMMUNITIES_SCROLLMAP],
            self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP => [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSections::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSections::MODULE_SCROLLMAP_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getFilterSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP:
                return [GD_URE_Module_Processor_CustomFilters::class, GD_URE_Module_Processor_CustomFilters::MODULE_FILTER_COMMUNITIES];

            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORCOMMUNITYMEMBERS];
        }

        return parent::getFilterSubmodule($component);
    }

    public function getFormat(array $component): ?string
    {
        $maps = array(
            [self::class, self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );
        if (in_array($component, $maps)) {
            $format = POP_FORMAT_MAP;
        }

        return $format ?? parent::getFormat($component);
    }

    // public function getNature(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
    //             return UserRequestNature::USER;
    //     }

    //     return parent::getNature($component);
    // }
    protected function getImmutableDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        switch ($component[1]) {
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

    protected function getMutableonrequestDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component[1]) {
         // Members of the Community
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                // If the profile is not a community, then return no users at all (Eg: a community opting out from having members)
                if (gdUreIsCommunity($author)) {
                    URE_CommunityUtils::addDataloadqueryargsCommunitymembers($ret, $author);
                }
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
         // Members of the Community
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                // If the profile is not a community, then return no users at all (Eg: a community opting out from having members)
                if (!gdUreIsCommunity($author)) {
                    $this->setProp($component, $props, 'skip-data-load', true);
                }
                break;
        }

        switch ($component[1]) {
            case self::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('members', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('communities', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}

