<?php

class PoP_Module_Processor_InstantaneousFilters extends PoP_Module_Processor_InstantaneousFiltersBase
{
    public const MODULE_INSTANTANEOUSFILTER_CONTENTSECTIONS = 'instantaneousfilter-contentsections';
    public const MODULE_INSTANTANEOUSFILTER_POSTSECTIONS = 'instantaneousfilter-postsections';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_INSTANTANEOUSFILTER_CONTENTSECTIONS],
            [self::class, self::MODULE_INSTANTANEOUSFILTER_POSTSECTIONS],
        );
    }
    
    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_INSTANTANEOUSFILTER_CONTENTSECTIONS => [PoP_Module_Processor_InstantaneousSimpleFilterInners::class, PoP_Module_Processor_InstantaneousSimpleFilterInners::MODULE_INSTANTANEOUSFILTERINNER_CONTENTSECTIONS],
            self::MODULE_INSTANTANEOUSFILTER_POSTSECTIONS => [PoP_Module_Processor_InstantaneousSimpleFilterInners::class, PoP_Module_Processor_InstantaneousSimpleFilterInners::MODULE_INSTANTANEOUSFILTERINNER_POSTSECTIONS],
        );

        if ($inner = $inners[$module[1]]) {
            return $inner;
        }
    
        return parent::getInnerSubmodule($module);
    }
}



