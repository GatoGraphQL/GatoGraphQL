<?php

class PoP_Locations_Module_Processor_CustomScrollMaps extends PoP_Module_Processor_ScrollMapsBase
{
    public final const COMPONENT_SCROLL_USERS_MAP = 'scroll-users-map';
    public final const COMPONENT_SCROLL_USER_MAP = 'scroll-user-map';
    public final const COMPONENT_SCROLL_USERS_HORIZONTALMAP = 'scroll-users-horizontalmap';
    public final const COMPONENT_SCROLL_LOCATIONS_MAP = 'scroll-locations-map';
    public final const COMPONENT_SCROLL_EVENTS_MAP = 'scroll-events-map';
    public final const COMPONENT_SCROLL_PASTEVENTS_MAP = 'scroll-pastevents-map';
    public final const COMPONENT_SCROLL_EVENTS_HORIZONTALMAP = 'scroll-events-horizontalmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLL_USERS_MAP],
            [self::class, self::COMPONENT_SCROLL_USER_MAP],
            [self::class, self::COMPONENT_SCROLL_USERS_HORIZONTALMAP],
            [self::class, self::COMPONENT_SCROLL_LOCATIONS_MAP],
            [self::class, self::COMPONENT_SCROLL_EVENTS_MAP],
            [self::class, self::COMPONENT_SCROLL_PASTEVENTS_MAP],
            [self::class, self::COMPONENT_SCROLL_EVENTS_HORIZONTALMAP],
        );
    }


    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_USERS_MAP => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_USERS_MAP],
            self::COMPONENT_SCROLL_USER_MAP => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_USER_MAP],
            self::COMPONENT_SCROLL_USERS_HORIZONTALMAP => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_USERS_HORIZONTALMAP],
            self::COMPONENT_SCROLL_LOCATIONS_MAP => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LOCATIONS_MAP],
            self::COMPONENT_SCROLL_EVENTS_MAP => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_EVENTS_MAP],
            self::COMPONENT_SCROLL_PASTEVENTS_MAP => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_PASTEVENTS_MAP],
            self::COMPONENT_SCROLL_EVENTS_HORIZONTALMAP => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_EVENTS_HORIZONTALMAP],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}


