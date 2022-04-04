<?php

class PoP_TrendingTags_Module_Processor_SectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_TRENDINGTAGS_SCROLL_DETAILS = 'block-trendingtags-scroll-details';
    public final const MODULE_BLOCK_TRENDINGTAGS_SCROLL_LIST = 'block-trendingtags-scroll-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TRENDINGTAGS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_TRENDINGTAGS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_TRENDINGTAGS_SCROLL_DETAILS => POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS,
            self::MODULE_BLOCK_TRENDINGTAGS_SCROLL_LIST => POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_TRENDINGTAGS_SCROLL_DETAILS => [PoP_TrendingTags_Module_Processor_SectionDataloads::class, PoP_TrendingTags_Module_Processor_SectionDataloads::MODULE_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS],
            self::MODULE_BLOCK_TRENDINGTAGS_SCROLL_LIST => [PoP_TrendingTags_Module_Processor_SectionDataloads::class, PoP_TrendingTags_Module_Processor_SectionDataloads::MODULE_DATALOAD_TRENDINGTAGS_SCROLL_LIST],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TRENDINGTAGS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TRENDINGTAGS_SCROLL_LIST:
                return [PoP_TrendingTags_Module_Processor_CustomControlGroups::class, PoP_TrendingTags_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_TRENDINGTAGLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }
}



