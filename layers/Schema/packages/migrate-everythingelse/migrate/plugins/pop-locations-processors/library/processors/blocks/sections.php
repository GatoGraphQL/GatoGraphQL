<?php

class PoP_Locations_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public const MODULE_BLOCK_LOCATIONS_SCROLL = 'block-locations-scroll';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_LOCATIONS_SCROLL],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_LOCATIONS_SCROLL => POP_LOCATIONS_ROUTE_LOCATIONS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_LOCATIONS_SCROLL => [PoP_Locations_Module_Processor_CustomSectionDataloads::class, PoP_Locations_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONS_SCROLL],
        );

        return $inner_modules[$module[1]];
    }
}



