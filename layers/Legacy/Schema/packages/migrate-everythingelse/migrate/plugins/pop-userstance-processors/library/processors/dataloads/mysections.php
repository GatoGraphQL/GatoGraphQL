<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\Stances\TypeResolvers\ObjectType\StanceObjectTypeResolver;

class UserStance_Module_Processor_MySectionDataloads extends PoP_Module_Processor_MySectionDataloadsBase
{
    public final const COMPONENT_DATALOAD_MYSTANCES_TABLE_EDIT = 'dataload-mystances-table-edit';
    public final const COMPONENT_DATALOAD_MYSTANCES_SCROLL_FULLVIEWPREVIEW = 'dataload-mystances-scroll-fullviewpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_MYSTANCES_TABLE_EDIT],
            [self::class, self::COMPONENT_DATALOAD_MYSTANCES_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_MYSTANCES_SCROLL_FULLVIEWPREVIEW => POP_USERSTANCE_ROUTE_MYSTANCES,
            self::COMPONENT_DATALOAD_MYSTANCES_TABLE_EDIT => POP_USERSTANCE_ROUTE_MYSTANCES,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_DATALOAD_MYSTANCES_TABLE_EDIT => [UserStance_Module_Processor_Tables::class, UserStance_Module_Processor_Tables::COMPONENT_TABLE_MYSTANCES],
            self::COMPONENT_DATALOAD_MYSTANCES_SCROLL_FULLVIEWPREVIEW => [UserStance_Module_Processor_CustomScrolls::class, UserStance_Module_Processor_CustomScrolls::COMPONENT_SCROLL_MYSTANCES_FULLVIEWPREVIEW],
        );

        return $inner_components[$component->name] ?? null;
    }

    public function getFilterSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_MYSTANCES_TABLE_EDIT:
            case self::COMPONENT_DATALOAD_MYSTANCES_SCROLL_FULLVIEWPREVIEW:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::COMPONENT_FILTER_MYSTANCES];
        }

        return parent::getFilterSubcomponent($component);
    }

    public function getFormat(\PoP\ComponentModel\Component\Component $component): ?string
    {
        $tables = array(
            [self::class, self::COMPONENT_DATALOAD_MYSTANCES_TABLE_EDIT],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_DATALOAD_MYSTANCES_SCROLL_FULLVIEWPREVIEW],
        );
        if (in_array($component, $tables)) {
            $format = POP_FORMAT_TABLE;
        } elseif (in_array($component, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        }

        return $format ?? parent::getFormat($component);
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_MYSTANCES_TABLE_EDIT:
            case self::COMPONENT_DATALOAD_MYSTANCES_SCROLL_FULLVIEWPREVIEW:
                return $this->instanceManager->getInstance(StanceObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_MYSTANCES_TABLE_EDIT:
            case self::COMPONENT_DATALOAD_MYSTANCES_SCROLL_FULLVIEWPREVIEW:
                $stances = PoP_UserStance_PostNameUtils::getNamesLc();
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', $stances);
                $this->setProp(
                    [GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN],
                    $props,
                    'action',
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('access your %s', 'poptheme-wassup'),
                        $stances
                    )
                );
                break;
        }
        parent::initModelProps($component, $props);
    }
}



