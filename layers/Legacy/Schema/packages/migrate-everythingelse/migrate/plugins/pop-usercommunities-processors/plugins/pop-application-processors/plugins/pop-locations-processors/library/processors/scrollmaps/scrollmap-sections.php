<?php

class PoP_UserCommunities_ModuleProcessor_CustomScrollMapSections extends GD_EM_Module_Processor_ScrollMapsBase
{
    public final const MODULE_SCROLLMAP_COMMUNITIES_SCROLLMAP = 'scrollmap-communities-scrollmap';
    public final const MODULE_SCROLLMAP_AUTHORCOMMUNITYMEMBERS_SCROLLMAP = 'scrollmap-authormembers-scrollmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLMAP_COMMUNITIES_SCROLLMAP],
            [self::class, self::MODULE_SCROLLMAP_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );
    }

    protected function isUserMap(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLMAP_COMMUNITIES_SCROLLMAP:
            case self::MODULE_SCROLLMAP_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                return true;
        }

        return parent::isUserMap($module);
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_SCROLLMAP_COMMUNITIES_SCROLLMAP => [PoP_UserCommunities_EM_ModuleProcessor_CustomScrollMaps::class, PoP_UserCommunities_EM_ModuleProcessor_CustomScrollMaps::MODULE_SCROLL_COMMUNITIES_MAP],
            self::MODULE_SCROLLMAP_AUTHORCOMMUNITYMEMBERS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
        );

        return $inner_modules[$module[1]] ?? null;
    }
}

