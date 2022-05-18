<?php

class PoP_Module_Processor_LocationsMapBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_LOCATIONSMAP = 'block-locationsmap';
    public final const MODULE_BLOCK_STATICLOCATIONSMAP = 'block-staticlocationsmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_LOCATIONSMAP],
            [self::class, self::MODULE_BLOCK_STATICLOCATIONSMAP],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_LOCATIONSMAP => POP_LOCATIONS_ROUTE_LOCATIONSMAP,
            self::MODULE_BLOCK_STATICLOCATIONSMAP => POP_LOCATIONS_ROUTE_LOCATIONSMAP,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_LOCATIONSMAP:
                $ret[] = [PoP_Module_Processor_LocationsMapDataloads::class, PoP_Module_Processor_LocationsMapDataloads::MODULE_DATALOAD_LOCATIONSMAP];
                break;

            case self::MODULE_BLOCK_STATICLOCATIONSMAP:
                $ret[] = [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAP_DIV];
                break;
        }

        return $ret;
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_STATICLOCATIONSMAP:
                return '';
        }

        return parent::getTitle($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_LOCATIONSMAP:
                // No need to show the locations list, only the map will do
                $this->appendProp([[PoP_Module_Processor_LocationsMapDataloads::class, PoP_Module_Processor_LocationsMapDataloads::MODULE_DATALOAD_LOCATIONSMAP], [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_LOCATIONS_MAP]], $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



