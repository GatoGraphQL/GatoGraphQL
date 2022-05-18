<?php

class GD_URE_Module_Processor_CustomScrollMapSectionBlocks extends GD_EM_Module_Processor_ScrollMapBlocksBase
{
    public final const MODULE_BLOCK_ORGANIZATIONS_SCROLLMAP = 'block-organizations-scrollmap';
    public final const MODULE_BLOCK_INDIVIDUALS_SCROLLMAP = 'block-individuals-scrollmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_ORGANIZATIONS_SCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_INDIVIDUALS_SCROLLMAP],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_INDIVIDUALS_SCROLLMAP => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS,
            self::COMPONENT_BLOCK_ORGANIZATIONS_SCROLLMAP => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_ORGANIZATIONS_SCROLLMAP => [GD_URE_Module_Processor_CustomScrollMapSectionDataloads::class, GD_URE_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLLMAP],
            self::COMPONENT_BLOCK_INDIVIDUALS_SCROLLMAP => [GD_URE_Module_Processor_CustomScrollMapSectionDataloads::class, GD_URE_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_INDIVIDUALS_SCROLLMAP],
        );

        return $inner_components[$component[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_ORGANIZATIONS_SCROLLMAP:
            case self::COMPONENT_BLOCK_INDIVIDUALS_SCROLLMAP:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKUSERLIST];
        }

        return parent::getControlgroupTopSubmodule($component);
    }
}



