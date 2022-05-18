<?php

class PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks extends PoP_Module_Processor_MySectionBlocksBase
{
    public final const MODULE_BLOCK_MYLINKS_TABLE_EDIT = 'block-mylinks-table-edit';
    public final const MODULE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW = 'block-mylinks-scroll-simpleviewpreview';
    public final const MODULE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW = 'block-mylinks-scroll-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_MYLINKS_TABLE_EDIT],
            [self::class, self::MODULE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW => POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS,
            self::MODULE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW => POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS,
            self::MODULE_BLOCK_MYLINKS_TABLE_EDIT => POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodule(array $componentVariation)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_MYLINKS_TABLE_EDIT => [PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYLINKS_TABLE_EDIT],
            self::MODULE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW => [PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
            self::MODULE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW => [PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYLINKS_SCROLL_FULLVIEWPREVIEW],
        );

        return $inner_modules[$componentVariation[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_MYLINKS_TABLE_EDIT:
            case self::MODULE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYBLOCKCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }
}



