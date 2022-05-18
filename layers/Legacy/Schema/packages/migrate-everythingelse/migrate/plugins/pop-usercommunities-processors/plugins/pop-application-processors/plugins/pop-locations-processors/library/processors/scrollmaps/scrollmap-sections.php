<?php

class PoP_UserCommunities_ComponentProcessor_CustomScrollMapSections extends GD_EM_Module_Processor_ScrollMapsBase
{
    public final const MODULE_SCROLLMAP_COMMUNITIES_SCROLLMAP = 'scrollmap-communities-scrollmap';
    public final const MODULE_SCROLLMAP_AUTHORCOMMUNITYMEMBERS_SCROLLMAP = 'scrollmap-authormembers-scrollmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLMAP_COMMUNITIES_SCROLLMAP],
            [self::class, self::COMPONENT_SCROLLMAP_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );
    }

    protected function isUserMap(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLMAP_COMMUNITIES_SCROLLMAP:
            case self::COMPONENT_SCROLLMAP_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                return true;
        }

        return parent::isUserMap($component);
    }

    public function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::COMPONENT_SCROLLMAP_COMMUNITIES_SCROLLMAP => [PoP_UserCommunities_EM_ComponentProcessor_CustomScrollMaps::class, PoP_UserCommunities_EM_ComponentProcessor_CustomScrollMaps::COMPONENT_SCROLL_COMMUNITIES_MAP],
            self::COMPONENT_SCROLLMAP_AUTHORCOMMUNITYMEMBERS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::COMPONENT_SCROLL_USERS_MAP],
        );

        return $inner_components[$component[1]] ?? null;
    }
}

