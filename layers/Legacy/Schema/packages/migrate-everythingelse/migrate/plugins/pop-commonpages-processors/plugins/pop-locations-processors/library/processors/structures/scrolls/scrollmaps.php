<?php

class PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollMaps extends PoP_Module_Processor_ScrollMapsBase
{
    public final const COMPONENT_SCROLL_WHOWEARE_MAP = 'scroll-whoweare-map';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLL_WHOWEARE_MAP],
        );
    }


    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_WHOWEARE_MAP => [PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollInners::class, PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_WHOWEARE_MAP],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}


