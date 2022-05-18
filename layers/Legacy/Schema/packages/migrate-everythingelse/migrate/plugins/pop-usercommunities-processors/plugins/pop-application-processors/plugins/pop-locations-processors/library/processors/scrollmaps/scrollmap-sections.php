<?php

class PoP_UserCommunities_ComponentProcessor_CustomScrollMapSections extends GD_EM_Module_Processor_ScrollMapsBase
{
    public final const MODULE_SCROLLMAP_COMMUNITIES_SCROLLMAP = 'scrollmap-communities-scrollmap';
    public final const MODULE_SCROLLMAP_AUTHORCOMMUNITYMEMBERS_SCROLLMAP = 'scrollmap-authormembers-scrollmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLMAP_COMMUNITIES_SCROLLMAP],
            [self::class, self::MODULE_SCROLLMAP_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );
    }

    protected function isUserMap(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLLMAP_COMMUNITIES_SCROLLMAP:
            case self::MODULE_SCROLLMAP_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                return true;
        }

        return parent::isUserMap($componentVariation);
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_modules = array(
            self::MODULE_SCROLLMAP_COMMUNITIES_SCROLLMAP => [PoP_UserCommunities_EM_ComponentProcessor_CustomScrollMaps::class, PoP_UserCommunities_EM_ComponentProcessor_CustomScrollMaps::MODULE_SCROLL_COMMUNITIES_MAP],
            self::MODULE_SCROLLMAP_AUTHORCOMMUNITYMEMBERS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
        );

        return $inner_modules[$componentVariation[1]] ?? null;
    }
}

