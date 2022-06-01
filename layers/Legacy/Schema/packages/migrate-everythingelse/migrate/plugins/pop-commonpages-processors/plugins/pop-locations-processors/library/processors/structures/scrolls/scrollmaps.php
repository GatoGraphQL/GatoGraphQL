<?php

class PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollMaps extends PoP_Module_Processor_ScrollMapsBase
{
    public final const COMPONENT_SCROLL_WHOWEARE_MAP = 'scroll-whoweare-map';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCROLL_WHOWEARE_MAP,
        );
    }


    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_WHOWEARE_MAP => [PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollInners::class, PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_WHOWEARE_MAP],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}


