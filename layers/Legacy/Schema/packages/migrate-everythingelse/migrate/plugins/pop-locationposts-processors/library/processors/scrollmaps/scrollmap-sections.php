<?php

class GD_Custom_Module_Processor_CustomScrollMapSections extends GD_EM_Module_Processor_ScrollMapsBase
{
    public final const MODULE_SCROLLMAP_LOCATIONPOSTS_SCROLLMAP = 'scrollmap-locationposts-scrollmap';
    public final const MODULE_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP = 'scrollmap-locationposts-horizontalscrollmap';
    public final const MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_SCROLLMAP = 'scrollmap-authorlocationposts-scrollmap';
    public final const MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP = 'scrollmap-authorlocationposts-horizontalscrollmap';
    public final const MODULE_SCROLLMAP_TAGLOCATIONPOSTS_SCROLLMAP = 'scrollmap-taglocationposts-scrollmap';
    public final const MODULE_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP = 'scrollmap-taglocationposts-horizontalscrollmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLMAP_LOCATIONPOSTS_SCROLLMAP],
            [self::class, self::MODULE_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP],
            [self::class, self::MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_SCROLLMAP],
            [self::class, self::MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP],
            [self::class, self::MODULE_SCROLLMAP_TAGLOCATIONPOSTS_SCROLLMAP],
            [self::class, self::MODULE_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP],
        );
    }

    protected function isPostMap(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLLMAP_LOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_SCROLLMAP_TAGLOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return true;
        }

        return parent::isPostMap($componentVariation);
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_modules = array(
            self::MODULE_SCROLLMAP_LOCATIONPOSTS_SCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::MODULE_SCROLL_LOCATIONPOSTS_MAP],
            self::MODULE_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::MODULE_SCROLL_LOCATIONPOSTS_HORIZONTALMAP],
            self::MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_SCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::MODULE_SCROLL_LOCATIONPOSTS_MAP],
            self::MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::MODULE_SCROLL_LOCATIONPOSTS_HORIZONTALMAP],
            self::MODULE_SCROLLMAP_TAGLOCATIONPOSTS_SCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::MODULE_SCROLL_LOCATIONPOSTS_MAP],
            self::MODULE_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::MODULE_SCROLL_LOCATIONPOSTS_HORIZONTALMAP],
        );

        return $inner_modules[$componentVariation[1]] ?? null;
    }

    public function getMapDirection(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return 'horizontal';
        }

        return parent::getMapDirection($componentVariation, $props);
    }
}



