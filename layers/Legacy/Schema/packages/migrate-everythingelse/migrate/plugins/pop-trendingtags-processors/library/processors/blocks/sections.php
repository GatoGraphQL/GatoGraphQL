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

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_DETAILS => POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS,
            self::COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_LIST => POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_DETAILS => [PoP_TrendingTags_Module_Processor_SectionDataloads::class, PoP_TrendingTags_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_LIST => [PoP_TrendingTags_Module_Processor_SectionDataloads::class, PoP_TrendingTags_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_LIST],
        );

        return $inner_components[$component[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_TRENDINGTAGS_SCROLL_LIST:
                return [PoP_TrendingTags_Module_Processor_CustomControlGroups::class, PoP_TrendingTags_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_TRENDINGTAGLIST];
        }

        return parent::getControlgroupTopSubmodule($component);
    }
}



