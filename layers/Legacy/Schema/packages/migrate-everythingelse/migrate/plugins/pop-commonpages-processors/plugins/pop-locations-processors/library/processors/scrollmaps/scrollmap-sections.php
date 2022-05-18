<?php

class GD_CommonPages_EM_Module_Processor_CustomScrollMapSections extends GD_EM_Module_Processor_ScrollMapsBase
{
    public final const MODULE_SCROLLMAP_WHOWEARE_SCROLLMAP = 'scrollmap-whoweare-scrollmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLMAP_WHOWEARE_SCROLLMAP],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::COMPONENT_SCROLLMAP_WHOWEARE_SCROLLMAP => [PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollMaps::class, PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollMaps::COMPONENT_SCROLL_WHOWEARE_MAP],
        );

        return $inner_components[$component[1]] ?? null;
    }

    protected function isUserMap(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLMAP_WHOWEARE_SCROLLMAP:
                return true;
        }

        return parent::isUserMap($component);
    }
}



