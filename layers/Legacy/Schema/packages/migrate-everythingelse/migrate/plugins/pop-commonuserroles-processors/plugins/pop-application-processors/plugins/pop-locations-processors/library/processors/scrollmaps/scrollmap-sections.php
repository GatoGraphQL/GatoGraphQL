<?php

class GD_URE_Module_Processor_CustomScrollMapSections extends GD_EM_Module_Processor_ScrollMapsBase
{
    public final const COMPONENT_SCROLLMAP_ORGANIZATIONS_SCROLLMAP = 'scrollmap-organizations-scrollmap';
    public final const COMPONENT_SCROLLMAP_INDIVIDUALS_SCROLLMAP = 'scrollmap-individuals-scrollmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLMAP_ORGANIZATIONS_SCROLLMAP],
            [self::class, self::COMPONENT_SCROLLMAP_INDIVIDUALS_SCROLLMAP],
        );
    }

    protected function isUserMap(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLMAP_ORGANIZATIONS_SCROLLMAP:
            case self::COMPONENT_SCROLLMAP_INDIVIDUALS_SCROLLMAP:
                return true;
        }

        return parent::isUserMap($component);
    }

    public function getInnerSubcomponent(array $component)
    {
        $inner_components = array(
            self::COMPONENT_SCROLLMAP_ORGANIZATIONS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::COMPONENT_SCROLL_USER_MAP],
            self::COMPONENT_SCROLLMAP_INDIVIDUALS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::COMPONENT_SCROLL_USER_MAP],
        );

        return $inner_components[$component[1]] ?? null;
    }
}



