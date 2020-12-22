<?php

class GD_Custom_Module_Processor_CustomScrollMapSections extends GD_EM_Module_Processor_ScrollMapsBase
{
    public const MODULE_SCROLLMAP_LOCATIONPOSTS_SCROLLMAP = 'scrollmap-locationposts-scrollmap';
    public const MODULE_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP = 'scrollmap-locationposts-horizontalscrollmap';
    public const MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_SCROLLMAP = 'scrollmap-authorlocationposts-scrollmap';
    public const MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP = 'scrollmap-authorlocationposts-horizontalscrollmap';
    public const MODULE_SCROLLMAP_TAGLOCATIONPOSTS_SCROLLMAP = 'scrollmap-taglocationposts-scrollmap';
    public const MODULE_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP = 'scrollmap-taglocationposts-horizontalscrollmap';

    public function getModulesToProcess(): array
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

    protected function isPostMap(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLMAP_LOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_SCROLLMAP_TAGLOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return true;
        }

        return parent::isPostMap($module);
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_SCROLLMAP_LOCATIONPOSTS_SCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::MODULE_SCROLL_LOCATIONPOSTS_MAP],
            self::MODULE_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::MODULE_SCROLL_LOCATIONPOSTS_HORIZONTALMAP],
            self::MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_SCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::MODULE_SCROLL_LOCATIONPOSTS_MAP],
            self::MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::MODULE_SCROLL_LOCATIONPOSTS_HORIZONTALMAP],
            self::MODULE_SCROLLMAP_TAGLOCATIONPOSTS_SCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::MODULE_SCROLL_LOCATIONPOSTS_MAP],
            self::MODULE_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP => [PoP_LocationPosts_Module_Processor_CustomScrollMaps::class, PoP_LocationPosts_Module_Processor_CustomScrollMaps::MODULE_SCROLL_LOCATIONPOSTS_HORIZONTALMAP],
        );

        return $inner_modules[$module[1]];
    }

    public function getMapDirection(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLMAP_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_SCROLLMAP_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_SCROLLMAP_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return 'horizontal';
        }

        return parent::getMapDirection($module, $props);
    }
}



