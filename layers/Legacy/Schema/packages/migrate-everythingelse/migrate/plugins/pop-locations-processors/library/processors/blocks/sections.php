<?php

class PoP_Locations_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_LOCATIONS_SCROLL = 'block-locations-scroll';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_LOCATIONS_SCROLL],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_BLOCK_LOCATIONS_SCROLL => POP_LOCATIONS_ROUTE_LOCATIONS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::MODULE_BLOCK_LOCATIONS_SCROLL => [PoP_Locations_Module_Processor_CustomSectionDataloads::class, PoP_Locations_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONS_SCROLL],
        );

        return $inner_components[$component[1]] ?? null;
    }
}



