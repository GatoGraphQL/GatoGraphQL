<?php
use PoPSchema\Stances\TypeResolvers\ObjectType\StanceObjectTypeResolver;

class UserStance_URE_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW = 'dataload-stances-byorganizations-scroll-fullview';
    public final const COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW = 'dataload-stances-byindividuals-scroll-fullview';
    public final const COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL = 'dataload-stances-byorganizations-scroll-thumbnail';
    public final const COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL = 'dataload-stances-byindividuals-scroll-thumbnail';
    public final const COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST = 'dataload-stances-byorganizations-scroll-list';
    public final const COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST = 'dataload-stances-byindividuals-scroll-list';
    public final const COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL = 'dataload-stances-byorganizations-carousel';
    public final const COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL = 'dataload-stances-byindividuals-carousel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL],
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL],
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL => POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL => POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Home/Page blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::COMPONENT_SCROLL_STANCES_FULLVIEW],
            self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::COMPONENT_SCROLL_STANCES_THUMBNAIL],
            self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::COMPONENT_SCROLL_STANCES_LIST],

            self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::COMPONENT_SCROLL_STANCES_FULLVIEW],
            self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::COMPONENT_SCROLL_STANCES_THUMBNAIL],
            self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::COMPONENT_SCROLL_STANCES_LIST],

            /*********************************************
             * Post Carousels
             *********************************************/

            self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL => [UserStance_Module_Processor_CustomCarousels::class, UserStance_Module_Processor_CustomCarousels::COMPONENT_CAROUSEL_STANCES_BYORGANIZATIONS],
            self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL => [UserStance_Module_Processor_CustomCarousels::class, UserStance_Module_Processor_CustomCarousels::COMPONENT_CAROUSEL_STANCES_BYINDIVIDUALS],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getFilterSubcomponent(\PoP\ComponentModel\Component\Component $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::COMPONENT_FILTER_STANCES_AUTHORROLE];
        }

        return parent::getFilterSubcomponent($component);
    }

    public function getFormat(\PoP\ComponentModel\Component\Component $component): ?string
    {
        $fullviews = array(
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST],
        );
        $carousels = array(
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL],
            [self::class, self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL],
        );
        if (in_array($component, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($component, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($component, $lists)) {
            $format = POP_FORMAT_LIST;
        } elseif (in_array($component, $carousels)) {
            $format = POP_FORMAT_CAROUSEL;
        }

        return $format ?? parent::getFormat($component);
    }

    protected function getImmutableDataloadQueryArgs(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        // switch ($component[1]) {

        //     case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW:
        //     case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL:
        //     case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST:

        //     case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW:
        //     case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL:
        //     case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST:

        //     case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL:
        //     case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL:

        //         $ret['custompost-types'] = [POP_USERSTANCE_POSTTYPE_USERSTANCE];
        //         break;
        // }

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL:
                $organizations = array(
                    [self::class, self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW],
                    [self::class, self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL],
                    [self::class, self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST],
                    [self::class, self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL],
                );
                $individuals = array(
                    [self::class, self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW],
                    [self::class, self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL],
                    [self::class, self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST],
                    [self::class, self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL],
                );
                if (in_array($component, $organizations)) {
                    $role = GD_URE_ROLE_ORGANIZATION;
                } elseif (in_array($component, $individuals)) {
                    $role = GD_URE_ROLE_INDIVIDUAL;
                }

                // It must fulfil 2 conditions: the user must've said he/she's a member of this organization,
                // And the Organization must've accepted it by leaving the Show As Member privilege on
                $ret['meta-query'][] = [
                    'key' => \PoPCMSSchema\CustomPostMeta\Utils::getMetaKey(GD_URE_METAKEY_POST_AUTHORROLE),
                    'value' => $role,
                ];
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL:
                return $this->instanceManager->getInstance(StanceObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL:
            case self::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', PoP_UserStance_PostNameUtils::getNamesLc());
                break;
        }

        parent::initModelProps($component, $props);
    }
}



