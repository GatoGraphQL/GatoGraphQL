<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Locations\TypeResolvers\LocationTypeResolver;

class PoP_Locations_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public const MODULE_DATALOAD_LOCATIONS_TYPEAHEAD = 'dataload-locations-typeahead';
    public const MODULE_DATALOAD_LOCATIONS_SCROLL = 'dataload-locations-scroll';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_LOCATIONS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_LOCATIONS_SCROLL],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_LOCATIONS_SCROLL => POP_LOCATIONS_ROUTE_LOCATIONS,
            self::MODULE_DATALOAD_LOCATIONS_TYPEAHEAD => POP_LOCATIONS_ROUTE_LOCATIONS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_LOCATIONS_TYPEAHEAD => [GD_EM_Module_Processor_LocationTypeaheadsComponentLayouts::class, GD_EM_Module_Processor_LocationTypeaheadsComponentLayouts::MODULE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_LOCATIONS_SCROLL => [PoP_Locations_Module_Processor_CustomScrolls::class, PoP_Locations_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONS],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOCATIONS_SCROLL:
            case self::MODULE_DATALOAD_LOCATIONS_TYPEAHEAD:
                return [PoP_Locations_Module_Processor_CustomFilters::class, PoP_Locations_Module_Processor_CustomFilters::MODULE_FILTER_LOCATIONS];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {

        // Add the format attr
        $typeaheads = array(
            [self::class, self::MODULE_DATALOAD_LOCATIONS_TYPEAHEAD],
        );
        if (in_array($module, $typeaheads)) {
            $format = POP_FORMAT_TYPEAHEAD;
        }

        return $format ?? parent::getFormat($module);
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOCATIONS_SCROLL:
            case self::MODULE_DATALOAD_LOCATIONS_TYPEAHEAD:
                return LocationTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOCATIONS_SCROLL:
            case self::MODULE_DATALOAD_LOCATIONS_TYPEAHEAD:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('locations', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}



