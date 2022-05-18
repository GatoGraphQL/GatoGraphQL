<?php

class PoP_AddHighlights_Module_Processor_MySectionBlocks extends PoP_Module_Processor_MySectionBlocksBase
{
    public final const MODULE_BLOCK_MYHIGHLIGHTS_TABLE_EDIT = 'block-myhighlights-table-edit';
    public final const MODULE_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW = 'block-myhighlights-scroll-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_MYHIGHLIGHTS_TABLE_EDIT],
            [self::class, self::MODULE_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW => POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS,
            self::MODULE_BLOCK_MYHIGHLIGHTS_TABLE_EDIT => POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodule(array $componentVariation)
    {
        $inner_componentVariations = array(
            self::MODULE_BLOCK_MYHIGHLIGHTS_TABLE_EDIT => [PoP_AddHighlights_Module_Processor_MySectionDataloads::class, PoP_AddHighlights_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYHIGHLIGHTS_TABLE_EDIT],
            self::MODULE_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW => [PoP_AddHighlights_Module_Processor_MySectionDataloads::class, PoP_AddHighlights_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW],
        );

        return $inner_componentVariations[$componentVariation[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_MYHIGHLIGHTS_TABLE_EDIT:
            case self::MODULE_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_AddHighlights_Module_Processor_CustomControlGroups::class, PoP_AddHighlights_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYHIGHLIGHTLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }
}



