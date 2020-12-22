<?php
use PoPSchema\Stances\TypeResolvers\StanceTypeResolver;

class UserStance_URE_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public const MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW = 'dataload-stances-byorganizations-scroll-fullview';
    public const MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW = 'dataload-stances-byindividuals-scroll-fullview';
    public const MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL = 'dataload-stances-byorganizations-scroll-thumbnail';
    public const MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL = 'dataload-stances-byindividuals-scroll-thumbnail';
    public const MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST = 'dataload-stances-byorganizations-scroll-list';
    public const MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST = 'dataload-stances-byindividuals-scroll-list';
    public const MODULE_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL = 'dataload-stances-byorganizations-carousel';
    public const MODULE_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL = 'dataload-stances-byindividuals-carousel';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL => POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL => POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
        * Home/Page blocks
        *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],

            self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_FULLVIEW],
            self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_THUMBNAIL],
            self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::MODULE_SCROLL_STANCES_LIST],

            /*********************************************
         * Post Carousels
         *********************************************/

            self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL => [UserStance_Module_Processor_CustomCarousels::class, UserStance_Module_Processor_CustomCarousels::MODULE_CAROUSEL_STANCES_BYORGANIZATIONS],
            self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL => [UserStance_Module_Processor_CustomCarousels::class, UserStance_Module_Processor_CustomCarousels::MODULE_CAROUSEL_STANCES_BYINDIVIDUALS],
        );

        return $inner_modules[$module[1]];
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_STANCES_AUTHORROLE];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST],
        );
        $carousels = array(
            [self::class, self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL],
        );
        if (in_array($module, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($module, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($module, $lists)) {
            $format = POP_FORMAT_LIST;
        } elseif (in_array($module, $carousels)) {
            $format = POP_FORMAT_CAROUSEL;
        }

        return $format ?? parent::getFormat($module);
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        // switch ($module[1]) {

        //     case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW:
        //     case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL:
        //     case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST:

        //     case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW:
        //     case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL:
        //     case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST:

        //     case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL:
        //     case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL:

        //         $ret['custompost-types'] = [POP_USERSTANCE_POSTTYPE_USERSTANCE];
        //         break;
        // }

        switch ($module[1]) {
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL:
                $organizations = array(
                    [self::class, self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW],
                    [self::class, self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL],
                    [self::class, self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST],
                    [self::class, self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL],
                );
                $individuals = array(
                    [self::class, self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW],
                    [self::class, self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL],
                    [self::class, self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST],
                    [self::class, self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL],
                );
                if (in_array($module, $organizations)) {
                    $role = GD_URE_ROLE_ORGANIZATION;
                } elseif (in_array($module, $individuals)) {
                    $role = GD_URE_ROLE_INDIVIDUAL;
                }

                // It must fulfil 2 conditions: the user must've said he/she's a member of this organization,
                // And the Organization must've accepted it by leaving the Show As Member privilege on
                $ret['meta-query'][] = [
                    'key' => \PoPSchema\CustomPostMeta\Utils::getMetaKey(GD_URE_METAKEY_POST_AUTHORROLE),
                    'value' => $role,
                ];
                break;
        }

        return $ret;
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL:
                return StanceTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST:
            case self::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL:
            case self::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', PoP_UserStance_PostNameUtils::getNamesLc());
                break;
        }

        parent::initModelProps($module, $props);
    }
}



