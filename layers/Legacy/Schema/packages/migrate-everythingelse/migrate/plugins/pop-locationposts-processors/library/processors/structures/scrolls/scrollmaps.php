<?php

class PoP_LocationPosts_Module_Processor_CustomScrollMaps extends PoP_Module_Processor_ScrollMapsBase
{
    public final const COMPONENT_SCROLL_LOCATIONPOSTS_MAP = 'scroll-locationposts-map';
    public final const COMPONENT_SCROLL_LOCATIONPOSTS_HORIZONTALMAP = 'scroll-locationposts-horizontalmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLL_LOCATIONPOSTS_MAP],
            [self::class, self::COMPONENT_SCROLL_LOCATIONPOSTS_HORIZONTALMAP],
        );
    }


    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_LOCATIONPOSTS_MAP => [PoP_LocationPosts_Module_Processor_CustomScrollInners::class, PoP_LocationPosts_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LOCATIONPOSTS_MAP],
            self::COMPONENT_SCROLL_LOCATIONPOSTS_HORIZONTALMAP => [PoP_LocationPosts_Module_Processor_CustomScrollInners::class, PoP_LocationPosts_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LOCATIONPOSTS_HORIZONTALMAP],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}


