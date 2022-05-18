<?php

class PoP_Locations_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_LOCATIONS_SCROLL = 'block-locations-scroll';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_LOCATIONS_SCROLL],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_LOCATIONS_SCROLL => POP_LOCATIONS_ROUTE_LOCATIONS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodule(array $componentVariation)
    {
        $inner_componentVariations = array(
            self::MODULE_BLOCK_LOCATIONS_SCROLL => [PoP_Locations_Module_Processor_CustomSectionDataloads::class, PoP_Locations_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONS_SCROLL],
        );

        return $inner_componentVariations[$componentVariation[1]] ?? null;
    }
}



