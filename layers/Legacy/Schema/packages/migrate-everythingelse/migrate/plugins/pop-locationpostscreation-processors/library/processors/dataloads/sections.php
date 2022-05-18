<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\LocationPosts\TypeResolvers\ObjectType\LocationPostObjectTypeResolver;

class GD_Custom_EM_Module_Processor_MySectionDataloads extends PoP_Module_Processor_MySectionDataloadsBase
{
    public final const MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT = 'dataload-mylocationposts-table-edit';
    public final const MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW = 'dataload-mylocationposts-scroll-simpleviewpreview';
    public final const MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW = 'dataload-mylocationposts-scroll-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT],
            [self::class, self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
            self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
            self::MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_componentVariations = array(
            self::MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT => [GD_Custom_EM_Module_Processor_Tables::class, GD_Custom_EM_Module_Processor_Tables::MODULE_TABLE_MYLOCATIONPOSTS],
            self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW => [GD_Custom_EM_Module_Processor_CustomScrolls::class, GD_Custom_EM_Module_Processor_CustomScrolls::MODULE_SCROLL_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW],
            self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW => [GD_Custom_EM_Module_Processor_CustomScrolls::class, GD_Custom_EM_Module_Processor_CustomScrolls::MODULE_SCROLL_MYLOCATIONPOSTS_FULLVIEWPREVIEW],
        );

        return $inner_componentVariations[$componentVariation[1]] ?? null;
    }

    public function getFilterSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW:
                return [GD_Custom_EM_Module_Processor_CustomFilters::class, GD_Custom_EM_Module_Processor_CustomFilters::MODULE_FILTER_MYLOCATIONPOSTS];
        }

        return parent::getFilterSubmodule($componentVariation);
    }

    public function getFormat(array $componentVariation): ?string
    {
        $tables = array(
            [self::class, self::MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT],
        );
        $simpleviews = array(
            [self::class, self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW],
        );
        if (in_array($componentVariation, $tables)) {
            $format = POP_FORMAT_TABLE;
        } elseif (in_array($componentVariation, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        } elseif (in_array($componentVariation, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        }

        return $format ?? parent::getFormat($componentVariation);
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW:
                return $this->instanceManager->getInstance(LocationPostObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW:
                $names = PoP_LocationPosts_PostNameUtils::getNamesLc();
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', $names);
                $this->setProp(
                    [GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN],
                    $props,
                    'action',
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('access your %s', 'poptheme-wassup'),
                        $names
                    )
                );
                break;
        }
        parent::initModelProps($componentVariation, $props);
    }
}



