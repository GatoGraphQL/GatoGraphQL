<?php

class GD_URE_Module_Processor_CustomScrollMapSectionBlocks extends GD_EM_Module_Processor_ScrollMapBlocksBase
{
    public final const MODULE_BLOCK_ORGANIZATIONS_SCROLLMAP = 'block-organizations-scrollmap';
    public final const MODULE_BLOCK_INDIVIDUALS_SCROLLMAP = 'block-individuals-scrollmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_ORGANIZATIONS_SCROLLMAP],
            [self::class, self::MODULE_BLOCK_INDIVIDUALS_SCROLLMAP],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_INDIVIDUALS_SCROLLMAP => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS,
            self::MODULE_BLOCK_ORGANIZATIONS_SCROLLMAP => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodule(array $componentVariation)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_ORGANIZATIONS_SCROLLMAP => [GD_URE_Module_Processor_CustomScrollMapSectionDataloads::class, GD_URE_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLLMAP],
            self::MODULE_BLOCK_INDIVIDUALS_SCROLLMAP => [GD_URE_Module_Processor_CustomScrollMapSectionDataloads::class, GD_URE_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLLMAP],
        );

        return $inner_modules[$componentVariation[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_ORGANIZATIONS_SCROLLMAP:
            case self::MODULE_BLOCK_INDIVIDUALS_SCROLLMAP:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKUSERLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }
}



