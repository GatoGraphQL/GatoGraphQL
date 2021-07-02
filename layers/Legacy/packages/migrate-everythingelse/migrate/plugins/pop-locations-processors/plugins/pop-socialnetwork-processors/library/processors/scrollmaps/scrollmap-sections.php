<?php

class PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections extends GD_EM_Module_Processor_ScrollMapsBase
{
    public const MODULE_SCROLLMAP_AUTHORFOLLOWERS_SCROLLMAP = 'scrollmap-authorfollowers-scrollmap';
    public const MODULE_SCROLLMAP_AUTHORFOLLOWINGUSERS_SCROLLMAP = 'scrollmap-authorfollowingusers-scrollmap';
    public const MODULE_SCROLLMAP_SINGLERECOMMENDEDBY_SCROLLMAP = 'scrollmap-singlerecommendedby-scrollmap';
    public const MODULE_SCROLLMAP_SINGLEUPVOTEDBY_SCROLLMAP = 'scrollmap-singleupvotedby-scrollmap';
    public const MODULE_SCROLLMAP_SINGLEDOWNVOTEDBY_SCROLLMAP = 'scrollmap-singledownvotedby-scrollmap';
    public const MODULE_SCROLLMAP_TAGSUBSCRIBERS_SCROLLMAP = 'scrollmap-tagsubscribers-scrollmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLMAP_AUTHORFOLLOWERS_SCROLLMAP],
            [self::class, self::MODULE_SCROLLMAP_AUTHORFOLLOWINGUSERS_SCROLLMAP],
            [self::class, self::MODULE_SCROLLMAP_SINGLERECOMMENDEDBY_SCROLLMAP],
            [self::class, self::MODULE_SCROLLMAP_SINGLEUPVOTEDBY_SCROLLMAP],
            [self::class, self::MODULE_SCROLLMAP_SINGLEDOWNVOTEDBY_SCROLLMAP],
            [self::class, self::MODULE_SCROLLMAP_TAGSUBSCRIBERS_SCROLLMAP],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_SCROLLMAP_AUTHORFOLLOWERS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
            self::MODULE_SCROLLMAP_AUTHORFOLLOWINGUSERS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
            self::MODULE_SCROLLMAP_SINGLERECOMMENDEDBY_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
            self::MODULE_SCROLLMAP_SINGLEUPVOTEDBY_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
            self::MODULE_SCROLLMAP_SINGLEDOWNVOTEDBY_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
            self::MODULE_SCROLLMAP_TAGSUBSCRIBERS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    protected function isUserMap(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLMAP_AUTHORFOLLOWERS_SCROLLMAP:
            case self::MODULE_SCROLLMAP_AUTHORFOLLOWINGUSERS_SCROLLMAP:
            case self::MODULE_SCROLLMAP_SINGLERECOMMENDEDBY_SCROLLMAP:
            case self::MODULE_SCROLLMAP_SINGLEUPVOTEDBY_SCROLLMAP:
            case self::MODULE_SCROLLMAP_SINGLEDOWNVOTEDBY_SCROLLMAP:
            case self::MODULE_SCROLLMAP_TAGSUBSCRIBERS_SCROLLMAP:
                return true;
        }

        return parent::isUserMap($module);
    }
}



