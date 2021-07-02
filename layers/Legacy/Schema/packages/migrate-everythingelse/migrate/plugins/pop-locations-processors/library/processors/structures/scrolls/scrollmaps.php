<?php

class PoP_Locations_Module_Processor_CustomScrollMaps extends PoP_Module_Processor_ScrollMapsBase
{
    public const MODULE_SCROLL_USERS_MAP = 'scroll-users-map';
    public const MODULE_SCROLL_USER_MAP = 'scroll-user-map';
    public const MODULE_SCROLL_USERS_HORIZONTALMAP = 'scroll-users-horizontalmap';
    public const MODULE_SCROLL_LOCATIONS_MAP = 'scroll-locations-map';
    public const MODULE_SCROLL_EVENTS_MAP = 'scroll-events-map';
    public const MODULE_SCROLL_PASTEVENTS_MAP = 'scroll-pastevents-map';
    public const MODULE_SCROLL_EVENTS_HORIZONTALMAP = 'scroll-events-horizontalmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_USERS_MAP],
            [self::class, self::MODULE_SCROLL_USER_MAP],
            [self::class, self::MODULE_SCROLL_USERS_HORIZONTALMAP],
            [self::class, self::MODULE_SCROLL_LOCATIONS_MAP],
            [self::class, self::MODULE_SCROLL_EVENTS_MAP],
            [self::class, self::MODULE_SCROLL_PASTEVENTS_MAP],
            [self::class, self::MODULE_SCROLL_EVENTS_HORIZONTALMAP],
        );
    }


    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_SCROLL_USERS_MAP => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USERS_MAP],
            self::MODULE_SCROLL_USER_MAP => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USER_MAP],
            self::MODULE_SCROLL_USERS_HORIZONTALMAP => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USERS_HORIZONTALMAP],
            self::MODULE_SCROLL_LOCATIONS_MAP => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_LOCATIONS_MAP],
            self::MODULE_SCROLL_EVENTS_MAP => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_EVENTS_MAP],
            self::MODULE_SCROLL_PASTEVENTS_MAP => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_PASTEVENTS_MAP],
            self::MODULE_SCROLL_EVENTS_HORIZONTALMAP => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_EVENTS_HORIZONTALMAP],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}


