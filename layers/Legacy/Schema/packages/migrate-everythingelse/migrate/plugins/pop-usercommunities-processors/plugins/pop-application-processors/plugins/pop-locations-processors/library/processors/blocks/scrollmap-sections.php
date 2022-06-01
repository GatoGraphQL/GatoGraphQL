<?php

class PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionBlocks extends GD_EM_Module_Processor_ScrollMapBlocksBase
{
    public final const COMPONENT_BLOCK_COMMUNITIES_SCROLLMAP = 'block-communities-scrollmap';
    public final const COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLLMAP = 'block-authormembers-scrollmap';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_COMMUNITIES_SCROLLMAP,
            self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLLMAP,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLLMAP => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::COMPONENT_BLOCK_COMMUNITIES_SCROLLMAP => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_COMMUNITIES_SCROLLMAP => [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLLMAP],
            self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLLMAP => [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );

        return $inner_components[$component->name] ?? null;
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_COMMUNITIES_SCROLLMAP:
            case self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLLMAP:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKUSERLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }
}

