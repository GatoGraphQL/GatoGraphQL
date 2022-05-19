<?php

class PoP_Locations_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const COMPONENT_BLOCK_LOCATIONS_SCROLL = 'block-locations-scroll';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_LOCATIONS_SCROLL],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_LOCATIONS_SCROLL => POP_LOCATIONS_ROUTE_LOCATIONS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(array $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_LOCATIONS_SCROLL => [PoP_Locations_Module_Processor_CustomSectionDataloads::class, PoP_Locations_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_LOCATIONS_SCROLL],
        );

        return $inner_components[$component[1]] ?? null;
    }
}



