<?php

class PoP_TrendingTags_Module_Processor_SectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_DETAILS = 'block-trendingtags-scroll-details';
    public final const COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_LIST = 'block-trendingtags-scroll-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_DETAILS => POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS,
            self::COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_LIST => POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_DETAILS => [PoP_TrendingTags_Module_Processor_SectionDataloads::class, PoP_TrendingTags_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_LIST => [PoP_TrendingTags_Module_Processor_SectionDataloads::class, PoP_TrendingTags_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_LIST],
        );

        return $inner_components[$component->name] ?? null;
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_LIST:
                return [PoP_TrendingTags_Module_Processor_CustomControlGroups::class, PoP_TrendingTags_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_TRENDINGTAGLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }
}



