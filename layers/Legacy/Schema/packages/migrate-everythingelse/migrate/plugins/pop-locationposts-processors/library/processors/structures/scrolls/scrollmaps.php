<?php

class PoP_LocationPosts_Module_Processor_CustomScrollMaps extends PoP_Module_Processor_ScrollMapsBase
{
    public const MODULE_SCROLL_LOCATIONPOSTS_MAP = 'scroll-locationposts-map';
    public const MODULE_SCROLL_LOCATIONPOSTS_HORIZONTALMAP = 'scroll-locationposts-horizontalmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_LOCATIONPOSTS_MAP],
            [self::class, self::MODULE_SCROLL_LOCATIONPOSTS_HORIZONTALMAP],
        );
    }


    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_SCROLL_LOCATIONPOSTS_MAP => [PoP_LocationPosts_Module_Processor_CustomScrollInners::class, PoP_LocationPosts_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_LOCATIONPOSTS_MAP],
            self::MODULE_SCROLL_LOCATIONPOSTS_HORIZONTALMAP => [PoP_LocationPosts_Module_Processor_CustomScrollInners::class, PoP_LocationPosts_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_LOCATIONPOSTS_HORIZONTALMAP],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}


