<?php

class PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSections extends GD_EM_Module_Processor_ScrollMapsBase
{
    public final const MODULE_SCROLLMAP_SINGLEAUTHORS_SCROLLMAP = 'scrollmap-singleauthors-scrollmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLMAP_SINGLEAUTHORS_SCROLLMAP],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_SCROLLMAP_SINGLEAUTHORS_SCROLLMAP => [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_USERS_MAP],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    protected function isUserMap(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLMAP_SINGLEAUTHORS_SCROLLMAP:
                return true;
        }

        return parent::isUserMap($module);
    }
}



