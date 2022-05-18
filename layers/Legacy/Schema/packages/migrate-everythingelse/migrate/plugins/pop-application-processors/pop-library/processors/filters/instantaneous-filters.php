<?php

class PoP_Module_Processor_InstantaneousFilters extends PoP_Module_Processor_InstantaneousFiltersBase
{
    public final const MODULE_INSTANTANEOUSFILTER_CONTENTSECTIONS = 'instantaneousfilter-contentsections';
    public final const MODULE_INSTANTANEOUSFILTER_POSTSECTIONS = 'instantaneousfilter-postsections';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_INSTANTANEOUSFILTER_CONTENTSECTIONS],
            [self::class, self::MODULE_INSTANTANEOUSFILTER_POSTSECTIONS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_INSTANTANEOUSFILTER_CONTENTSECTIONS => [PoP_Module_Processor_InstantaneousSimpleFilterInners::class, PoP_Module_Processor_InstantaneousSimpleFilterInners::MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS],
            self::MODULE_INSTANTANEOUSFILTER_POSTSECTIONS => [PoP_Module_Processor_InstantaneousSimpleFilterInners::class, PoP_Module_Processor_InstantaneousSimpleFilterInners::MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



