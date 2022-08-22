<?php

class GD_URE_Module_Processor_CustomScrollMapSectionBlocks extends GD_EM_Module_Processor_ScrollMapBlocksBase
{
    public final const COMPONENT_BLOCK_ORGANIZATIONS_SCROLLMAP = 'block-organizations-scrollmap';
    public final const COMPONENT_BLOCK_INDIVIDUALS_SCROLLMAP = 'block-individuals-scrollmap';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_ORGANIZATIONS_SCROLLMAP,
            self::COMPONENT_BLOCK_INDIVIDUALS_SCROLLMAP,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_INDIVIDUALS_SCROLLMAP => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS,
            self::COMPONENT_BLOCK_ORGANIZATIONS_SCROLLMAP => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_ORGANIZATIONS_SCROLLMAP => [GD_URE_Module_Processor_CustomScrollMapSectionDataloads::class, GD_URE_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLLMAP],
            self::COMPONENT_BLOCK_INDIVIDUALS_SCROLLMAP => [GD_URE_Module_Processor_CustomScrollMapSectionDataloads::class, GD_URE_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_INDIVIDUALS_SCROLLMAP],
        );

        return $inner_components[$component->name] ?? null;
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_ORGANIZATIONS_SCROLLMAP:
            case self::COMPONENT_BLOCK_INDIVIDUALS_SCROLLMAP:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKUSERLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }
}



