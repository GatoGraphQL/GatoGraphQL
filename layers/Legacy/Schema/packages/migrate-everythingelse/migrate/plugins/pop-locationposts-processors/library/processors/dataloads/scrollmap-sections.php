<?php
use PoPCMSSchema\LocationPosts\TypeResolvers\ObjectType\LocationPostObjectTypeResolver;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class GD_Custom_Module_Processor_CustomScrollMapSectionDataloads extends GD_EM_Module_Processor_ScrollMapDataloadsBase
{
    public final const COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLLMAP = 'dataload-locationposts-scrollmap';
    public final const COMPONENT_DATALOAD_LOCATIONPOSTS_HORIZONTALSCROLLMAP = 'dataload-locationposts-horizontalscrollmap';
    public final const COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLLMAP = 'dataload-authorlocationposts-scrollmap';
    public final const COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP = 'dataload-authorlocationposts-horizontalscrollmap';
    public final const COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP = 'dataload-taglocationposts-scrollmap';
    public final const COMPONENT_DATALOAD_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP = 'dataload-taglocationposts-horizontalscrollmap';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLLMAP,
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_HORIZONTALSCROLLMAP,
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLLMAP,
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP,
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP,
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_HORIZONTALSCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSections::class, GD_Custom_Module_Processor_CustomScrollMapSections::COMPONENT_SCROLLMAP_LOCATIONPOSTS_SCROLLMAP],
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_HORIZONTALSCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSections::class, GD_Custom_Module_Processor_CustomScrollMapSections::COMPONENT_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP],
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSections::class, GD_Custom_Module_Processor_CustomScrollMapSections::COMPONENT_SCROLLMAP_AUTHORLOCATIONPOSTS_SCROLLMAP],
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSections::class, GD_Custom_Module_Processor_CustomScrollMapSections::COMPONENT_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP],
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSections::class, GD_Custom_Module_Processor_CustomScrollMapSections::COMPONENT_SCROLLMAP_TAGLOCATIONPOSTS_SCROLLMAP],
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSections::class, GD_Custom_Module_Processor_CustomScrollMapSections::COMPONENT_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP],
        );

        return $inner_components[$component->name] ?? null;
    }

    public function getFilterSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoP_LocationPosts_Module_Processor_CustomFilters::class, PoP_LocationPosts_Module_Processor_CustomFilters::COMPONENT_FILTER_LOCATIONPOSTS];

            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoP_LocationPosts_Module_Processor_CustomFilters::class, PoP_LocationPosts_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORLOCATIONPOSTS];

            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoP_LocationPosts_Module_Processor_CustomFilters::class, PoP_LocationPosts_Module_Processor_CustomFilters::COMPONENT_FILTER_TAGLOCATIONPOSTS];
        }

        return parent::getFilterSubcomponent($component);
    }

    public function getFormat(\PoP\ComponentModel\Component\Component $component): ?string
    {
        $maps = array(
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLLMAP,
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLLMAP,
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP,
        );
        $horizontalmaps = array(
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_HORIZONTALSCROLLMAP,
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP,
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP,
        );

        if (in_array($component, $maps)) {
            $format = POP_FORMAT_MAP;
        } elseif (in_array($component, $horizontalmaps)) {
            $format = POP_FORMAT_HORIZONTALMAP;
        }

        return $format ?? parent::getFormat($component);
    }
    // public function getNature(\PoP\ComponentModel\Component\Component $component)
    // {
    //     switch ($component->name) {
    //         case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLLMAP:
    //         case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
    //             return UserRequestNature::USER;

    //         case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP:
    //         case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
    //             return TagRequestNature::TAG;
    //     }

    //     return parent::getNature($component);
    // }

    protected function getMutableonrequestDataloadQueryArgs(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component->name) {
         // Filter by the Profile/Community
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorcontent($ret);
                break;

            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagcontent($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return $this->instanceManager->getInstance(LocationPostObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', PoP_LocationPosts_PostNameUtils::getNamesLc());
                break;
        }

        parent::initModelProps($component, $props);
    }
}



