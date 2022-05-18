<?php

class PoP_Module_Processor_LocationsMapBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_LOCATIONSMAP = 'block-locationsmap';
    public final const MODULE_BLOCK_STATICLOCATIONSMAP = 'block-staticlocationsmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_LOCATIONSMAP],
            [self::class, self::COMPONENT_BLOCK_STATICLOCATIONSMAP],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_LOCATIONSMAP => POP_LOCATIONS_ROUTE_LOCATIONSMAP,
            self::COMPONENT_BLOCK_STATICLOCATIONSMAP => POP_LOCATIONS_ROUTE_LOCATIONSMAP,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_LOCATIONSMAP:
                $ret[] = [PoP_Module_Processor_LocationsMapDataloads::class, PoP_Module_Processor_LocationsMapDataloads::COMPONENT_DATALOAD_LOCATIONSMAP];
                break;

            case self::COMPONENT_BLOCK_STATICLOCATIONSMAP:
                $ret[] = [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::COMPONENT_MAP_DIV];
                break;
        }

        return $ret;
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_STATICLOCATIONSMAP:
                return '';
        }

        return parent::getTitle($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_LOCATIONSMAP:
                // No need to show the locations list, only the map will do
                $this->appendProp([[PoP_Module_Processor_LocationsMapDataloads::class, PoP_Module_Processor_LocationsMapDataloads::COMPONENT_DATALOAD_LOCATIONSMAP], [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::COMPONENT_SCROLL_LOCATIONS_MAP]], $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



