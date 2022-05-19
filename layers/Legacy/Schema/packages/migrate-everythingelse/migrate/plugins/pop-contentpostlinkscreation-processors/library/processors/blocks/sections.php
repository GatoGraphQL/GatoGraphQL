<?php

class PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks extends PoP_Module_Processor_MySectionBlocksBase
{
    public final const COMPONENT_BLOCK_MYLINKS_TABLE_EDIT = 'block-mylinks-table-edit';
    public final const COMPONENT_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW = 'block-mylinks-scroll-simpleviewpreview';
    public final const COMPONENT_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW = 'block-mylinks-scroll-fullviewpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_MYLINKS_TABLE_EDIT],
            [self::class, self::COMPONENT_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::COMPONENT_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW => POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW => POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS,
            self::COMPONENT_BLOCK_MYLINKS_TABLE_EDIT => POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(array $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_MYLINKS_TABLE_EDIT => [PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYLINKS_TABLE_EDIT],
            self::COMPONENT_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW => [PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
            self::COMPONENT_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW => [PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYLINKS_SCROLL_FULLVIEWPREVIEW],
        );

        return $inner_components[$component[1]] ?? null;
    }

    protected function getControlgroupTopSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_MYLINKS_TABLE_EDIT:
            case self::COMPONENT_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYBLOCKCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }
}



