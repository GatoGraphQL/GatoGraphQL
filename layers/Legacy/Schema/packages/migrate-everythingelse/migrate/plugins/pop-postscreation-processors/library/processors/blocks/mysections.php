<?php

class PoP_PostsCreation_Module_Processor_MySectionBlocks extends PoP_Module_Processor_MySectionBlocksBase
{
    public final const COMPONENT_BLOCK_MYPOSTS_TABLE_EDIT = 'block-myposts-table-edit';
    public final const COMPONENT_BLOCK_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW = 'block-myposts-scroll-simpleviewpreview';
    public final const COMPONENT_BLOCK_MYPOSTS_SCROLL_FULLVIEWPREVIEW = 'block-myposts-scroll-fullviewpreview';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_MYPOSTS_TABLE_EDIT,
            self::COMPONENT_BLOCK_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW,
            self::COMPONENT_BLOCK_MYPOSTS_SCROLL_FULLVIEWPREVIEW,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_MYPOSTS_SCROLL_FULLVIEWPREVIEW => POP_POSTSCREATION_ROUTE_MYPOSTS,
            self::COMPONENT_BLOCK_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW => POP_POSTSCREATION_ROUTE_MYPOSTS,
            self::COMPONENT_BLOCK_MYPOSTS_TABLE_EDIT => POP_POSTSCREATION_ROUTE_MYPOSTS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_MYPOSTS_TABLE_EDIT => [PoP_PostsCreation_Module_Processor_MySectionDataloads::class, PoP_PostsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPOSTS_TABLE_EDIT],
            self::COMPONENT_BLOCK_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW => [PoP_PostsCreation_Module_Processor_MySectionDataloads::class, PoP_PostsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
            self::COMPONENT_BLOCK_MYPOSTS_SCROLL_FULLVIEWPREVIEW => [PoP_PostsCreation_Module_Processor_MySectionDataloads::class, PoP_PostsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW],
        );

        return $inner_components[$component->name] ?? null;
    }

    protected function getSectionFilterComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_MYPOSTS_TABLE_EDIT:
            case self::COMPONENT_BLOCK_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_BLOCK_MYPOSTS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_InstantaneousFilters::class, PoP_Module_Processor_InstantaneousFilters::COMPONENT_INSTANTANEOUSFILTER_POSTSECTIONS];
        }

        return parent::getSectionFilterComponent($component);
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_MYPOSTS_TABLE_EDIT:
            case self::COMPONENT_BLOCK_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_BLOCK_MYPOSTS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYBLOCKPOSTLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }
}



