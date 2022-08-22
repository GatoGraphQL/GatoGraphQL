<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Locations\TypeResolvers\ObjectType\LocationObjectTypeResolver;

class PoP_Locations_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const COMPONENT_DATALOAD_LOCATIONS_TYPEAHEAD = 'dataload-locations-typeahead';
    public final const COMPONENT_DATALOAD_LOCATIONS_SCROLL = 'dataload-locations-scroll';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_LOCATIONS_TYPEAHEAD,
            self::COMPONENT_DATALOAD_LOCATIONS_SCROLL,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_LOCATIONS_SCROLL => POP_LOCATIONS_ROUTE_LOCATIONS,
            self::COMPONENT_DATALOAD_LOCATIONS_TYPEAHEAD => POP_LOCATIONS_ROUTE_LOCATIONS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_DATALOAD_LOCATIONS_TYPEAHEAD => [GD_EM_Module_Processor_LocationTypeaheadsComponentLayouts::class, GD_EM_Module_Processor_LocationTypeaheadsComponentLayouts::COMPONENT_LAYOUTLOCATION_TYPEAHEAD_COMPONENT],
            self::COMPONENT_DATALOAD_LOCATIONS_SCROLL => [PoP_Locations_Module_Processor_CustomScrolls::class, PoP_Locations_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONS],
        );

        return $inner_components[$component->name] ?? null;
    }

    public function getFilterSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LOCATIONS_SCROLL:
            case self::COMPONENT_DATALOAD_LOCATIONS_TYPEAHEAD:
                return [PoP_Locations_Module_Processor_CustomFilters::class, PoP_Locations_Module_Processor_CustomFilters::COMPONENT_FILTER_LOCATIONS];
        }

        return parent::getFilterSubcomponent($component);
    }

    public function getFormat(\PoP\ComponentModel\Component\Component $component): ?string
    {

        // Add the format attr
        $typeaheads = array(
            self::COMPONENT_DATALOAD_LOCATIONS_TYPEAHEAD,
        );
        if (in_array($component, $typeaheads)) {
            $format = POP_FORMAT_TYPEAHEAD;
        }

        return $format ?? parent::getFormat($component);
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LOCATIONS_SCROLL:
            case self::COMPONENT_DATALOAD_LOCATIONS_TYPEAHEAD:
                return $this->instanceManager->getInstance(LocationObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LOCATIONS_SCROLL:
            case self::COMPONENT_DATALOAD_LOCATIONS_TYPEAHEAD:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('locations', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



