<?php

class PoP_Events_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_PASTEVENTS_SCROLL_NAVIGATOR = 'block-pastevents-scroll-navigator';
    public final const MODULE_BLOCK_EVENTS_SCROLL_NAVIGATOR = 'block-events-scroll-navigator';
    public final const MODULE_BLOCK_EVENTSCALENDAR_CALENDAR_NAVIGATOR = 'block-eventscalendar-calendar-navigator';
    public final const MODULE_BLOCK_PASTEVENTS_SCROLL_ADDONS = 'block-pastevents-scroll-addons';
    public final const MODULE_BLOCK_EVENTS_SCROLL_ADDONS = 'block-events-scroll-addons';
    public final const MODULE_BLOCK_EVENTSCALENDAR_CALENDAR_ADDONS = 'block-eventscalendar-calendar-addons';
    public final const MODULE_BLOCK_EVENTS_SCROLL_DETAILS = 'block-events-scroll-details';
    public final const MODULE_BLOCK_PASTEVENTS_SCROLL_DETAILS = 'block-pastevents-scroll-details';
    public final const MODULE_BLOCK_AUTHOREVENTS_SCROLL_DETAILS = 'block-authorevents-scroll-details';
    public final const MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_DETAILS = 'block-authorpastevents-scroll-details';
    public final const MODULE_BLOCK_TAGEVENTS_SCROLL_DETAILS = 'block-tagevents-scroll-details';
    public final const MODULE_BLOCK_TAGPASTEVENTS_SCROLL_DETAILS = 'block-tagpastevents-scroll-details';
    public final const MODULE_BLOCK_EVENTS_SCROLL_SIMPLEVIEW = 'block-events-scroll-simpleview';
    public final const MODULE_BLOCK_PASTEVENTS_SCROLL_SIMPLEVIEW = 'block-pastevents-scroll-simpleview';
    public final const MODULE_BLOCK_EVENTS_SCROLL_FULLVIEW = 'block-events-scroll-fullview';
    public final const MODULE_BLOCK_PASTEVENTS_SCROLL_FULLVIEW = 'block-pastevents-scroll-fullview';
    public final const MODULE_BLOCK_AUTHOREVENTS_SCROLL_SIMPLEVIEW = 'block-authorevents-scroll-simpleview';
    public final const MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW = 'block-authorpastevents-scroll-simpleview';
    public final const MODULE_BLOCK_AUTHOREVENTS_SCROLL_FULLVIEW = 'block-authorevents-scroll-fullview';
    public final const MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_FULLVIEW = 'block-authorpastevents-scroll-fullview';
    public final const MODULE_BLOCK_TAGEVENTS_SCROLL_SIMPLEVIEW = 'block-tagevents-scroll-simpleview';
    public final const MODULE_BLOCK_TAGPASTEVENTS_SCROLL_SIMPLEVIEW = 'block-tagpastevents-scroll-simpleview';
    public final const MODULE_BLOCK_TAGEVENTS_SCROLL_FULLVIEW = 'block-tagevents-scroll-fullview';
    public final const MODULE_BLOCK_TAGPASTEVENTS_SCROLL_FULLVIEW = 'block-tagpastevents-scroll-fullview';
    public final const MODULE_BLOCK_EVENTS_SCROLL_THUMBNAIL = 'block-events-scroll-thumbnail';
    public final const MODULE_BLOCK_PASTEVENTS_SCROLL_THUMBNAIL = 'block-pastevents-scroll-thumbnail';
    public final const MODULE_BLOCK_AUTHOREVENTS_SCROLL_THUMBNAIL = 'block-authorevents-scroll-thumbnail';
    public final const MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_THUMBNAIL = 'block-authorpastevents-scroll-thumbnail';
    public final const MODULE_BLOCK_TAGEVENTS_SCROLL_THUMBNAIL = 'block-tagevents-scroll-thumbnail';
    public final const MODULE_BLOCK_TAGPASTEVENTS_SCROLL_THUMBNAIL = 'block-tagpastevents-scroll-thumbnail';
    public final const MODULE_BLOCK_EVENTS_SCROLL_LIST = 'block-events-scroll-list';
    public final const MODULE_BLOCK_PASTEVENTS_SCROLL_LIST = 'block-pastevents-scroll-list';
    public final const MODULE_BLOCK_AUTHOREVENTS_SCROLL_LIST = 'block-authorevents-scroll-list';
    public final const MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_LIST = 'block-authorpastevents-scroll-list';
    public final const MODULE_BLOCK_TAGEVENTS_SCROLL_LIST = 'block-tagevents-scroll-list';
    public final const MODULE_BLOCK_TAGPASTEVENTS_SCROLL_LIST = 'block-tagpastevents-scroll-list';
    public final const MODULE_BLOCK_EVENTSCALENDAR_CALENDARMAP = 'block-eventscalendar-calendarmap';
    public final const MODULE_BLOCK_AUTHOREVENTSCALENDAR_CALENDARMAP = 'block-authoreventscalendar-calendarmap';
    public final const MODULE_BLOCK_TAGEVENTSCALENDAR_CALENDARMAP = 'block-tageventscalendar-calendarmap';
    public final const MODULE_BLOCK_EVENTSCALENDAR_CALENDAR = 'block-eventscalendar-calendar';
    public final const MODULE_BLOCK_AUTHOREVENTSCALENDAR_CALENDAR = 'block-authoreventscalendar-calendar';
    public final const MODULE_BLOCK_TAGEVENTSCALENDAR_CALENDAR = 'block-tageventscalendar-calendar';
    public final const MODULE_BLOCK_EVENTS_CAROUSEL = 'block-events-carousel';
    public final const MODULE_BLOCK_AUTHOREVENTS_CAROUSEL = 'block-authorevents-carousel';
    public final const MODULE_BLOCK_TAGEVENTS_CAROUSEL = 'block-tagevents-carousel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_PASTEVENTS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_BLOCK_EVENTS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR_NAVIGATOR],
            [self::class, self::MODULE_BLOCK_PASTEVENTS_SCROLL_ADDONS],
            [self::class, self::MODULE_BLOCK_EVENTS_SCROLL_ADDONS],
            [self::class, self::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR_ADDONS],
            [self::class, self::MODULE_BLOCK_EVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_PASTEVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_EVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_PASTEVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_EVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_PASTEVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_EVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_PASTEVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_EVENTS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_PASTEVENTS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_EVENTSCALENDAR_CALENDARMAP],
            [self::class, self::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR],

            [self::class, self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHOREVENTSCALENDAR_CALENDARMAP],
            [self::class, self::MODULE_BLOCK_AUTHOREVENTSCALENDAR_CALENDAR],

            [self::class, self::MODULE_BLOCK_TAGEVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_TAGEVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_TAGEVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_TAGEVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_TAGEVENTS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_TAGEVENTSCALENDAR_CALENDARMAP],
            [self::class, self::MODULE_BLOCK_TAGEVENTSCALENDAR_CALENDAR],

            [self::class, self::MODULE_BLOCK_EVENTS_CAROUSEL],
            [self::class, self::MODULE_BLOCK_AUTHOREVENTS_CAROUSEL],
            [self::class, self::MODULE_BLOCK_TAGEVENTS_CAROUSEL],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_BLOCK_AUTHOREVENTS_CAROUSEL => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_DETAILS => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_FULLVIEW => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_LIST => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_SIMPLEVIEW => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_THUMBNAIL => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_AUTHOREVENTSCALENDAR_CALENDAR => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_BLOCK_AUTHOREVENTSCALENDAR_CALENDARMAP => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_DETAILS => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_FULLVIEW => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_LIST => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_THUMBNAIL => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_EVENTS_CAROUSEL => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_EVENTS_SCROLL_ADDONS => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_EVENTS_SCROLL_DETAILS => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_EVENTS_SCROLL_FULLVIEW => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_EVENTS_SCROLL_LIST => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_EVENTS_SCROLL_NAVIGATOR => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_EVENTS_SCROLL_SIMPLEVIEW => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_EVENTS_SCROLL_THUMBNAIL => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR_ADDONS => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR_NAVIGATOR => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_BLOCK_EVENTSCALENDAR_CALENDARMAP => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_BLOCK_PASTEVENTS_SCROLL_ADDONS => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_PASTEVENTS_SCROLL_DETAILS => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_PASTEVENTS_SCROLL_FULLVIEW => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_PASTEVENTS_SCROLL_LIST => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_PASTEVENTS_SCROLL_NAVIGATOR => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_PASTEVENTS_SCROLL_SIMPLEVIEW => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_PASTEVENTS_SCROLL_THUMBNAIL => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_TAGEVENTS_CAROUSEL => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_TAGEVENTS_SCROLL_DETAILS => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_TAGEVENTS_SCROLL_FULLVIEW => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_TAGEVENTS_SCROLL_LIST => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_TAGEVENTS_SCROLL_SIMPLEVIEW => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_TAGEVENTS_SCROLL_THUMBNAIL => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_BLOCK_TAGEVENTSCALENDAR_CALENDAR => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_BLOCK_TAGEVENTSCALENDAR_CALENDARMAP => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_DETAILS => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_FULLVIEW => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_LIST => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_SIMPLEVIEW => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_THUMBNAIL => POP_EVENTS_ROUTE_PASTEVENTS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::MODULE_BLOCK_EVENTSCALENDAR_CALENDARMAP => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTSCALENDAR_CALENDARMAP],
            self::MODULE_BLOCK_AUTHOREVENTSCALENDAR_CALENDARMAP => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDARMAP],
            self::MODULE_BLOCK_TAGEVENTSCALENDAR_CALENDARMAP => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDARMAP],
            self::MODULE_BLOCK_PASTEVENTS_SCROLL_NAVIGATOR => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_NAVIGATOR],
            self::MODULE_BLOCK_EVENTS_SCROLL_NAVIGATOR => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_NAVIGATOR],
            self::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR_NAVIGATOR => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_NAVIGATOR],
            self::MODULE_BLOCK_PASTEVENTS_SCROLL_ADDONS => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_ADDONS],
            self::MODULE_BLOCK_EVENTS_SCROLL_ADDONS => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_ADDONS],
            self::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR_ADDONS => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_ADDONS],
            self::MODULE_BLOCK_EVENTS_SCROLL_DETAILS => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_DETAILS],
            self::MODULE_BLOCK_PASTEVENTS_SCROLL_DETAILS => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_DETAILS],
            self::MODULE_BLOCK_EVENTS_SCROLL_SIMPLEVIEW => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_PASTEVENTS_SCROLL_SIMPLEVIEW => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_EVENTS_SCROLL_FULLVIEW => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_PASTEVENTS_SCROLL_FULLVIEW => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_EVENTS_SCROLL_THUMBNAIL => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_PASTEVENTS_SCROLL_THUMBNAIL => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_EVENTS_SCROLL_LIST => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_LIST],
            self::MODULE_BLOCK_PASTEVENTS_SCROLL_LIST => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_LIST],
            self::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR],
            self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_DETAILS => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_DETAILS],
            self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_DETAILS => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS],
            self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_SIMPLEVIEW => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW],//[self::class, self::MODULE_SCROLL_AUTHOREVENTS_SIMPLEVIEW],
            self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW],//[self::class, self::MODULE_SCROLL_AUTHORPASTEVENTS_SIMPLEVIEW],
            self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_FULLVIEW => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_FULLVIEW => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_THUMBNAIL => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_THUMBNAIL => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_LIST => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_LIST => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHOREVENTSCALENDAR_CALENDAR => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDAR],
            self::MODULE_BLOCK_TAGEVENTS_SCROLL_DETAILS => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGEVENTS_SCROLL_DETAILS],
            self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_DETAILS => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_DETAILS],
            self::MODULE_BLOCK_TAGEVENTS_SCROLL_SIMPLEVIEW => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGEVENTS_SCROLL_SIMPLEVIEW],//[self::class, self::MODULE_SCROLL_AUTHOREVENTS_SIMPLEVIEW],
            self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_SIMPLEVIEW => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_SIMPLEVIEW],//[self::class, self::MODULE_SCROLL_AUTHORPASTEVENTS_SIMPLEVIEW],
            self::MODULE_BLOCK_TAGEVENTS_SCROLL_FULLVIEW => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGEVENTS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_FULLVIEW => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_TAGEVENTS_SCROLL_THUMBNAIL => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGEVENTS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_THUMBNAIL => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_TAGEVENTS_SCROLL_LIST => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGEVENTS_SCROLL_LIST],
            self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_LIST => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_LIST],
            self::MODULE_BLOCK_TAGEVENTSCALENDAR_CALENDAR => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDAR],
            self::MODULE_BLOCK_EVENTS_CAROUSEL => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_CAROUSEL],
            self::MODULE_BLOCK_AUTHOREVENTS_CAROUSEL => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHOREVENTS_CAROUSEL],
            self::MODULE_BLOCK_TAGEVENTS_CAROUSEL => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGEVENTS_CAROUSEL],
        );

        return $inner_components[$component[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_PASTEVENTS_SCROLL_ADDONS:
            case self::MODULE_BLOCK_EVENTS_SCROLL_ADDONS:
            case self::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR_ADDONS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SUBMENUSHARE];

            case self::MODULE_BLOCK_EVENTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_EVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_EVENTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_EVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_EVENTS_SCROLL_LIST:
                return [PoP_Events_Module_Processor_CustomControlGroups::class, PoP_Events_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKEVENTLIST];

            case self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHOREVENTS_SCROLL_LIST:
                return [PoP_Events_Module_Processor_CustomControlGroups::class, PoP_Events_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKAUTHOREVENTLIST];

            case self::MODULE_BLOCK_TAGEVENTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TAGEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_TAGEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_TAGEVENTS_SCROLL_LIST:
                return [PoP_Events_Module_Processor_CustomControlGroups::class, PoP_Events_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKTAGEVENTLIST];

            case self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_LIST:
            case self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHOREVENTSCALENDAR_CALENDAR:
                // Allow URE to add the ContentSource switch
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKAUTHORPOSTLIST];

            case self::MODULE_BLOCK_PASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_PASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_PASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_PASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_PASTEVENTS_SCROLL_LIST:
            case self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_LIST:
            case self::MODULE_BLOCK_PASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_PASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_EVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR:
            case self::MODULE_BLOCK_TAGEVENTSCALENDAR_CALENDAR:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($component);
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_EVENTS_CAROUSEL:
            case self::MODULE_BLOCK_AUTHOREVENTS_CAROUSEL:
            case self::MODULE_BLOCK_TAGEVENTS_CAROUSEL:
                return '';
        }

        return parent::getTitle($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_EVENTS_CAROUSEL:
            case self::MODULE_BLOCK_AUTHOREVENTS_CAROUSEL:
            case self::MODULE_BLOCK_TAGEVENTS_CAROUSEL:
                // Artificial property added to identify the template when adding module-resources
                // $this->setProp($component, $props, 'resourceloader', 'block-carousel');
                $this->appendProp($component, $props, 'class', 'pop-block-carousel block-posts-carousel block-events-carousel');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



