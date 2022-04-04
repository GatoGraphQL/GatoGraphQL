<?php

class GD_URE_Module_Processor_CustomScrollMapSections extends GD_EM_Module_Processor_ScrollMapsBase
{
    public final const MODULE_SCROLLMAP_ORGANIZATIONS_SCROLLMAP = 'scrollmap-organizations-scrollmap';
    public final const MODULE_SCROLLMAP_INDIVIDUALS_SCROLLMAP = 'scrollmap-individuals-scrollmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLMAP_ORGANIZATIONS_SCROLLMAP],
            [self::class, self::MODULE_SCROLLMAP_INDIVIDUALS_SCROLLMAP],
        );
    }

    protected function isUserMap(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLMAP_ORGANIZATIONS_SCROLLMAP:
            case self::MODULE_SCROLLMAP_INDIVIDUALS_SCROLLMAP:
                return true;
        }

        return parent::isUserMap($module);
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_SCROLLMAP_ORGANIZATIONS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USER_MAP],
            self::MODULE_SCROLLMAP_INDIVIDUALS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USER_MAP],
        );

        return $inner_modules[$module[1]] ?? null;
    }
}



