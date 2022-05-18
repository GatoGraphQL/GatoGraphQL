<?php

class GD_CommonPages_EM_Module_Processor_CustomScrollMapSections extends GD_EM_Module_Processor_ScrollMapsBase
{
    public final const MODULE_SCROLLMAP_WHOWEARE_SCROLLMAP = 'scrollmap-whoweare-scrollmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLMAP_WHOWEARE_SCROLLMAP],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_componentVariations = array(
            self::MODULE_SCROLLMAP_WHOWEARE_SCROLLMAP => [PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollMaps::class, PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_WHOWEARE_MAP],
        );

        return $inner_componentVariations[$componentVariation[1]] ?? null;
    }

    protected function isUserMap(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLLMAP_WHOWEARE_SCROLLMAP:
                return true;
        }

        return parent::isUserMap($componentVariation);
    }
}



