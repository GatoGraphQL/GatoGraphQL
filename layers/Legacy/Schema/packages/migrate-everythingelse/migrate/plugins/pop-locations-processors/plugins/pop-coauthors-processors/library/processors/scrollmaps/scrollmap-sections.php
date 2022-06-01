<?php

class PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSections extends GD_EM_Module_Processor_ScrollMapsBase
{
    public final const COMPONENT_SCROLLMAP_SINGLEAUTHORS_SCROLLMAP = 'scrollmap-singleauthors-scrollmap';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCROLLMAP_SINGLEAUTHORS_SCROLLMAP,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_SCROLLMAP_SINGLEAUTHORS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::COMPONENT_SCROLL_USERS_MAP],
        );

        return $inner_components[$component->name] ?? null;
    }

    protected function isUserMap(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLLMAP_SINGLEAUTHORS_SCROLLMAP:
                return true;
        }

        return parent::isUserMap($component);
    }
}



