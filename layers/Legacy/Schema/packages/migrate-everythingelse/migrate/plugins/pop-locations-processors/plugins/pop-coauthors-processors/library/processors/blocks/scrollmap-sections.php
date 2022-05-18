<?php

class PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionBlocks extends GD_EM_Module_Processor_ScrollMapBlocksBase
{
    public final const MODULE_BLOCK_SINGLEAUTHORS_SCROLLMAP = 'block-singleauthors-scrollmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_SINGLEAUTHORS_SCROLLMAP],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_BLOCK_SINGLEAUTHORS_SCROLLMAP => POP_ROUTE_AUTHORS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::MODULE_BLOCK_SINGLEAUTHORS_SCROLLMAP => [PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_SINGLEAUTHORS_SCROLLMAP:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getSingleTitle();
        }

        return parent::getTitle($component, $props);
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_SINGLEAUTHORS_SCROLLMAP:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKUSERLIST];
        }

        return parent::getControlgroupTopSubmodule($component);
    }
}



