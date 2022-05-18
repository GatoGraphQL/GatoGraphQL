<?php

class GD_Custom_Module_Processor_CustomScrollMapSections extends GD_EM_Module_Processor_ScrollMapsBase
{
    public final const COMPONENT_SCROLLMAP_LOCATIONPOSTS_SCROLLMAP = 'scrollmap-locationposts-scrollmap';
    public final const COMPONENT_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP = 'scrollmap-locationposts-horizontalscrollmap';
    public final const COMPONENT_SCROLLMAP_AUTHORLOCATIONPOSTS_SCROLLMAP = 'scrollmap-authorlocationposts-scrollmap';
    public final const COMPONENT_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP = 'scrollmap-authorlocationposts-horizontalscrollmap';
    public final const COMPONENT_SCROLLMAP_TAGLOCATIONPOSTS_SCROLLMAP = 'scrollmap-taglocationposts-scrollmap';
    public final const COMPONENT_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP = 'scrollmap-taglocationposts-horizontalscrollmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLMAP_LOCATIONPOSTS_SCROLLMAP],
            [self::class, self::COMPONENT_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP],
            [self::class, self::COMPONENT_SCROLLMAP_AUTHORLOCATIONPOSTS_SCROLLMAP],
            [self::class, self::COMPONENT_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP],
            [self::class, self::COMPONENT_SCROLLMAP_TAGLOCATIONPOSTS_SCROLLMAP],
            [self::class, self::COMPONENT_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP],
        );
    }

    protected function isPostMap(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLMAP_LOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_SCROLLMAP_AUTHORLOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_SCROLLMAP_TAGLOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return true;
        }

        return parent::isPostMap($component);
    }

    public function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::COMPONENT_SCROLLMAP_LOCATIONPOSTS_SCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::COMPONENT_SCROLL_LOCATIONPOSTS_MAP],
            self::COMPONENT_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::COMPONENT_SCROLL_LOCATIONPOSTS_HORIZONTALMAP],
            self::COMPONENT_SCROLLMAP_AUTHORLOCATIONPOSTS_SCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::COMPONENT_SCROLL_LOCATIONPOSTS_MAP],
            self::COMPONENT_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::COMPONENT_SCROLL_LOCATIONPOSTS_HORIZONTALMAP],
            self::COMPONENT_SCROLLMAP_TAGLOCATIONPOSTS_SCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::COMPONENT_SCROLL_LOCATIONPOSTS_MAP],
            self::COMPONENT_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::COMPONENT_SCROLL_LOCATIONPOSTS_HORIZONTALMAP],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getMapDirection(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return 'horizontal';
        }

        return parent::getMapDirection($component, $props);
    }
}



