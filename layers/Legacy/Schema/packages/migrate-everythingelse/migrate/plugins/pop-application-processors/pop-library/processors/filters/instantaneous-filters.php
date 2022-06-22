<?php

class PoP_Module_Processor_InstantaneousFilters extends PoP_Module_Processor_InstantaneousFiltersBase
{
    public final const COMPONENT_INSTANTANEOUSFILTER_CONTENTSECTIONS = 'instantaneousfilter-contentsections';
    public final const COMPONENT_INSTANTANEOUSFILTER_POSTSECTIONS = 'instantaneousfilter-postsections';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_INSTANTANEOUSFILTER_CONTENTSECTIONS,
            self::COMPONENT_INSTANTANEOUSFILTER_POSTSECTIONS,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_INSTANTANEOUSFILTER_CONTENTSECTIONS => [PoP_Module_Processor_InstantaneousSimpleFilterInners::class, PoP_Module_Processor_InstantaneousSimpleFilterInners::COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS],
            self::COMPONENT_INSTANTANEOUSFILTER_POSTSECTIONS => [PoP_Module_Processor_InstantaneousSimpleFilterInners::class, PoP_Module_Processor_InstantaneousSimpleFilterInners::COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



