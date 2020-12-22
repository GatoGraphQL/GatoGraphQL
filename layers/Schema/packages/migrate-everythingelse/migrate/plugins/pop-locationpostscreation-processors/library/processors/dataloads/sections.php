<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\LocationPosts\TypeResolvers\LocationPostTypeResolver;

class GD_Custom_EM_Module_Processor_MySectionDataloads extends PoP_Module_Processor_MySectionDataloadsBase
{
    public const MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT = 'dataload-mylocationposts-table-edit';
    public const MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW = 'dataload-mylocationposts-scroll-simpleviewpreview';
    public const MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW = 'dataload-mylocationposts-scroll-fullviewpreview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT],
            [self::class, self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
            self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
            self::MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT => [GD_Custom_EM_Module_Processor_Tables::class, GD_Custom_EM_Module_Processor_Tables::MODULE_TABLE_MYLOCATIONPOSTS],
            self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW => [GD_Custom_EM_Module_Processor_CustomScrolls::class, GD_Custom_EM_Module_Processor_CustomScrolls::MODULE_SCROLL_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW],
            self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW => [GD_Custom_EM_Module_Processor_CustomScrolls::class, GD_Custom_EM_Module_Processor_CustomScrolls::MODULE_SCROLL_MYLOCATIONPOSTS_FULLVIEWPREVIEW],
        );

        return $inner_modules[$module[1]];
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW:
                return [GD_Custom_EM_Module_Processor_CustomFilters::class, GD_Custom_EM_Module_Processor_CustomFilters::MODULE_FILTER_MYLOCATIONPOSTS];
        }
        
        return parent::getFilterSubmodule($module);
    }
    
    public function getFormat(array $module): ?string
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
        if (in_array($module, $tables)) {
            $format = POP_FORMAT_TABLE;
        } elseif (in_array($module, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        } elseif (in_array($module, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        }

        return $format ?? parent::getFormat($module);
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW:
                return LocationPostTypeResolver::class;
        }
            
        return parent::getTypeResolverClass($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
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
        parent::initModelProps($module, $props);
    }
}



