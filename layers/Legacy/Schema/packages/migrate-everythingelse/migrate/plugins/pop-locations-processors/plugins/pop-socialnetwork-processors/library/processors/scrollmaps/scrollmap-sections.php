<?php

class PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSections extends GD_EM_Module_Processor_ScrollMapsBase
{
    public final const MODULE_SCROLLMAP_AUTHORFOLLOWERS_SCROLLMAP = 'scrollmap-authorfollowers-scrollmap';
    public final const MODULE_SCROLLMAP_AUTHORFOLLOWINGUSERS_SCROLLMAP = 'scrollmap-authorfollowingusers-scrollmap';
    public final const MODULE_SCROLLMAP_SINGLERECOMMENDEDBY_SCROLLMAP = 'scrollmap-singlerecommendedby-scrollmap';
    public final const MODULE_SCROLLMAP_SINGLEUPVOTEDBY_SCROLLMAP = 'scrollmap-singleupvotedby-scrollmap';
    public final const MODULE_SCROLLMAP_SINGLEDOWNVOTEDBY_SCROLLMAP = 'scrollmap-singledownvotedby-scrollmap';
    public final const MODULE_SCROLLMAP_TAGSUBSCRIBERS_SCROLLMAP = 'scrollmap-tagsubscribers-scrollmap';

    public function getComponentVariationsToProcess(): array
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

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_componentVariations = array(
            self::MODULE_SCROLLMAP_AUTHORFOLLOWERS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
            self::MODULE_SCROLLMAP_AUTHORFOLLOWINGUSERS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
            self::MODULE_SCROLLMAP_SINGLERECOMMENDEDBY_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
            self::MODULE_SCROLLMAP_SINGLEUPVOTEDBY_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
            self::MODULE_SCROLLMAP_SINGLEDOWNVOTEDBY_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
            self::MODULE_SCROLLMAP_TAGSUBSCRIBERS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
        );

        return $inner_componentVariations[$componentVariation[1]] ?? null;
    }

    protected function isUserMap(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLLMAP_AUTHORFOLLOWERS_SCROLLMAP:
            case self::MODULE_SCROLLMAP_AUTHORFOLLOWINGUSERS_SCROLLMAP:
            case self::MODULE_SCROLLMAP_SINGLERECOMMENDEDBY_SCROLLMAP:
            case self::MODULE_SCROLLMAP_SINGLEUPVOTEDBY_SCROLLMAP:
            case self::MODULE_SCROLLMAP_SINGLEDOWNVOTEDBY_SCROLLMAP:
            case self::MODULE_SCROLLMAP_TAGSUBSCRIBERS_SCROLLMAP:
                return true;
        }

        return parent::isUserMap($componentVariation);
    }
}



