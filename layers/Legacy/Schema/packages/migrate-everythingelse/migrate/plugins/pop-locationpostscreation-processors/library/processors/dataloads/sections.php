<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\LocationPosts\TypeResolvers\ObjectType\LocationPostObjectTypeResolver;

class GD_Custom_EM_Module_Processor_MySectionDataloads extends PoP_Module_Processor_MySectionDataloadsBase
{
    public final const COMPONENT_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT = 'dataload-mylocationposts-table-edit';
    public final const COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW = 'dataload-mylocationposts-scroll-simpleviewpreview';
    public final const COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW = 'dataload-mylocationposts-scroll-fullviewpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT],
            [self::class, self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
            self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
            self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT => [GD_Custom_EM_Module_Processor_Tables::class, GD_Custom_EM_Module_Processor_Tables::COMPONENT_TABLE_MYLOCATIONPOSTS],
            self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW => [GD_Custom_EM_Module_Processor_CustomScrolls::class, GD_Custom_EM_Module_Processor_CustomScrolls::COMPONENT_SCROLL_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW],
            self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW => [GD_Custom_EM_Module_Processor_CustomScrolls::class, GD_Custom_EM_Module_Processor_CustomScrolls::COMPONENT_SCROLL_MYLOCATIONPOSTS_FULLVIEWPREVIEW],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getFilterSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT:
            case self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW:
                return [GD_Custom_EM_Module_Processor_CustomFilters::class, GD_Custom_EM_Module_Processor_CustomFilters::COMPONENT_FILTER_MYLOCATIONPOSTS];
        }

        return parent::getFilterSubmodule($component);
    }

    public function getFormat(array $component): ?string
    {
        $tables = array(
            [self::class, self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT],
        );
        $simpleviews = array(
            [self::class, self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW],
        );
        if (in_array($component, $tables)) {
            $format = POP_FORMAT_TABLE;
        } elseif (in_array($component, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        } elseif (in_array($component, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        }

        return $format ?? parent::getFormat($component);
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT:
            case self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW:
                return $this->instanceManager->getInstance(LocationPostObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT:
            case self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW:
                $names = PoP_LocationPosts_PostNameUtils::getNamesLc();
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', $names);
                $this->setProp(
                    [GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN],
                    $props,
                    'action',
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('access your %s', 'poptheme-wassup'),
                        $names
                    )
                );
                break;
        }
        parent::initModelProps($component, $props);
    }
}



