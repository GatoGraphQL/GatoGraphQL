<?php

class GD_Custom_EM_Module_Processor_MySectionBlocks extends PoP_Module_Processor_MySectionBlocksBase
{
    public final const COMPONENT_BLOCK_MYLOCATIONPOSTS_TABLE_EDIT = 'block-mylocationposts-table-edit';
    public final const COMPONENT_BLOCK_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW = 'block-mylocationposts-scroll-simpleviewpreview';
    public final const COMPONENT_BLOCK_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW = 'block-mylocationposts-scroll-fullviewpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_MYLOCATIONPOSTS_TABLE_EDIT],
            [self::class, self::COMPONENT_BLOCK_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::COMPONENT_BLOCK_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
            self::COMPONENT_BLOCK_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
            self::COMPONENT_BLOCK_MYLOCATIONPOSTS_TABLE_EDIT => POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_MYLOCATIONPOSTS_TABLE_EDIT => [GD_Custom_EM_Module_Processor_MySectionDataloads::class, GD_Custom_EM_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT],
            self::COMPONENT_BLOCK_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW => [GD_Custom_EM_Module_Processor_MySectionDataloads::class, GD_Custom_EM_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
            self::COMPONENT_BLOCK_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW => [GD_Custom_EM_Module_Processor_MySectionDataloads::class, GD_Custom_EM_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW],
        );

        return $inner_components[$component->name] ?? null;
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_MYLOCATIONPOSTS_TABLE_EDIT:
            case self::COMPONENT_BLOCK_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_BLOCK_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYBLOCKCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }
}



