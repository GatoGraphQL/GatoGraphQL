<?php

class PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionBlocks extends GD_EM_Module_Processor_ScrollMapBlocksBase
{
    public final const COMPONENT_BLOCK_SINGLEAUTHORS_SCROLLMAP = 'block-singleauthors-scrollmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLLMAP],
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLLMAP => POP_ROUTE_AUTHORS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLLMAP => [PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLLMAP],
        );

        return $inner_components[$component->name] ?? null;
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLLMAP:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getSingleTitle();
        }

        return parent::getTitle($component, $props);
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLLMAP:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKUSERLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }
}



