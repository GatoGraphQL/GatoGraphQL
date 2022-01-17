<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Events\ModuleProcessors\PastEventModuleProcessorTrait;
use PoPCMSSchema\Events\TypeResolvers\ObjectType\EventObjectTypeResolver;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_Events_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    use PastEventModuleProcessorTrait;

    public const MODULE_DATALOAD_EVENTS_TYPEAHEAD = 'dataload-events-typeahead';
    public const MODULE_DATALOAD_PASTEVENTS_TYPEAHEAD = 'dataload-pastevents-typeahead';
    public const MODULE_DATALOAD_PASTEVENTS_SCROLL_NAVIGATOR = 'dataload-pastevents-scroll-navigator';
    public const MODULE_DATALOAD_EVENTS_SCROLL_NAVIGATOR = 'dataload-events-scroll-navigator';
    public const MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_NAVIGATOR = 'dataload-eventscalendar-calendar-navigator';
    public const MODULE_DATALOAD_PASTEVENTS_SCROLL_ADDONS = 'dataload-pastevents-scroll-addons';
    public const MODULE_DATALOAD_EVENTS_SCROLL_ADDONS = 'dataload-events-scroll-addons';
    public const MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_ADDONS = 'dataload-eventscalendar-calendar-addons';
    public const MODULE_DATALOAD_EVENTS_SCROLL_DETAILS = 'dataload-events-scroll-details';
    public const MODULE_DATALOAD_PASTEVENTS_SCROLL_DETAILS = 'dataload-pastevents-scroll-details';
    public const MODULE_DATALOAD_AUTHOREVENTS_SCROLL_DETAILS = 'dataload-authorevents-scroll-details';
    public const MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS = 'dataload-authorpastevents-scroll-details';
    public const MODULE_DATALOAD_TAGEVENTS_SCROLL_DETAILS = 'dataload-tagevents-scroll-details';
    public const MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_DETAILS = 'dataload-tagpastevents-scroll-details';
    public const MODULE_DATALOAD_EVENTS_SCROLL_SIMPLEVIEW = 'dataload-events-scroll-simpleview';
    public const MODULE_DATALOAD_PASTEVENTS_SCROLL_SIMPLEVIEW = 'dataload-pastevents-scroll-simpleview';
    public const MODULE_DATALOAD_EVENTS_SCROLL_FULLVIEW = 'dataload-events-scroll-fullview';
    public const MODULE_DATALOAD_PASTEVENTS_SCROLL_FULLVIEW = 'dataload-pastevents-scroll-fullview';
    public const MODULE_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW = 'dataload-authorevents-scroll-simpleview';
    public const MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW = 'dataload-authorpastevents-scroll-simpleview';
    public const MODULE_DATALOAD_AUTHOREVENTS_SCROLL_FULLVIEW = 'dataload-authorevents-scroll-fullview';
    public const MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW = 'dataload-authorpastevents-scroll-fullview';
    public const MODULE_DATALOAD_TAGEVENTS_SCROLL_SIMPLEVIEW = 'dataload-tagevents-scroll-simpleview';
    public const MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_SIMPLEVIEW = 'dataload-tagpastevents-scroll-simpleview';
    public const MODULE_DATALOAD_TAGEVENTS_SCROLL_FULLVIEW = 'dataload-tagevents-scroll-fullview';
    public const MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_FULLVIEW = 'dataload-tagpastevents-scroll-fullview';
    public const MODULE_DATALOAD_EVENTS_SCROLL_THUMBNAIL = 'dataload-events-scroll-thumbnail';
    public const MODULE_DATALOAD_PASTEVENTS_SCROLL_THUMBNAIL = 'dataload-pastevents-scroll-thumbnail';
    public const MODULE_DATALOAD_AUTHOREVENTS_SCROLL_THUMBNAIL = 'dataload-authorevents-scroll-thumbnail';
    public const MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL = 'dataload-authorpastevents-scroll-thumbnail';
    public const MODULE_DATALOAD_TAGEVENTS_SCROLL_THUMBNAIL = 'dataload-tagevents-scroll-thumbnail';
    public const MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_THUMBNAIL = 'dataload-tagpastevents-scroll-thumbnail';
    public const MODULE_DATALOAD_EVENTS_SCROLL_LIST = 'dataload-events-scroll-list';
    public const MODULE_DATALOAD_PASTEVENTS_SCROLL_LIST = 'dataload-pastevents-scroll-list';
    public const MODULE_DATALOAD_AUTHOREVENTS_SCROLL_LIST = 'dataload-authorevents-scroll-list';
    public const MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST = 'dataload-authorpastevents-scroll-list';
    public const MODULE_DATALOAD_TAGEVENTS_SCROLL_LIST = 'dataload-tagevents-scroll-list';
    public const MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_LIST = 'dataload-tagpastevents-scroll-list';
    public const MODULE_DATALOAD_EVENTSCALENDAR_CALENDARMAP = 'dataload-eventscalendar-calendarmap';
    public const MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDARMAP = 'dataload-authoreventscalendar-calendarmap';
    public const MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDARMAP = 'dataload-tageventscalendar-calendarmap';
    public const MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR = 'dataload-eventscalendar-calendar';
    public const MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDAR = 'dataload-authoreventscalendar-calendar';
    public const MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDAR = 'dataload-tageventscalendar-calendar';
    public const MODULE_DATALOAD_EVENTS_CAROUSEL = 'dataload-events-carousel';
    public const MODULE_DATALOAD_AUTHOREVENTS_CAROUSEL = 'dataload-authorevents-carousel';
    public const MODULE_DATALOAD_TAGEVENTS_CAROUSEL = 'dataload-tagevents-carousel';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_EVENTS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_ADDONS],
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDARMAP],
            [self::class, self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR],

            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDARMAP],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDAR],

            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDARMAP],
            [self::class, self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDAR],

            [self::class, self::MODULE_DATALOAD_EVENTS_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_CAROUSEL],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_AUTHOREVENTS_CAROUSEL => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_DETAILS => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_FULLVIEW => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_LIST => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_THUMBNAIL => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDAR => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDARMAP => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_EVENTS_CAROUSEL => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_EVENTS_SCROLL_ADDONS => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_EVENTS_SCROLL_DETAILS => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_EVENTS_SCROLL_FULLVIEW => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_EVENTS_SCROLL_LIST => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_EVENTS_SCROLL_NAVIGATOR => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_EVENTS_SCROLL_SIMPLEVIEW => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_EVENTS_SCROLL_THUMBNAIL => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_EVENTS_TYPEAHEAD => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_ADDONS => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_NAVIGATOR => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDARMAP => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_DATALOAD_PASTEVENTS_SCROLL_ADDONS => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_PASTEVENTS_SCROLL_DETAILS => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_PASTEVENTS_SCROLL_FULLVIEW => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_PASTEVENTS_SCROLL_LIST => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_PASTEVENTS_SCROLL_NAVIGATOR => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_PASTEVENTS_SCROLL_SIMPLEVIEW => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_PASTEVENTS_SCROLL_THUMBNAIL => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_PASTEVENTS_TYPEAHEAD => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_TAGEVENTS_CAROUSEL => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_TAGEVENTS_SCROLL_DETAILS => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_TAGEVENTS_SCROLL_FULLVIEW => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_TAGEVENTS_SCROLL_LIST => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_TAGEVENTS_SCROLL_SIMPLEVIEW => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_TAGEVENTS_SCROLL_THUMBNAIL => POP_EVENTS_ROUTE_EVENTS,
            self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDAR => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDARMAP => POP_EVENTS_ROUTE_EVENTSCALENDAR,
            self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_DETAILS => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_FULLVIEW => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_LIST => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_SIMPLEVIEW => POP_EVENTS_ROUTE_PASTEVENTS,
            self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_THUMBNAIL => POP_EVENTS_ROUTE_PASTEVENTS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodules(array $module): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getInnerSubmodules($module);

        // if it's a map, add the Map block. Do it before adding the Scroll, because otherwise there's an error:
        // The map is not created yet, however the links in the elements are already trying to add the markers
        if ($map_inner_module = $this->getPostmapInnerModule($module)) {
            $ret[] = [GD_EM_Module_Processor_Maps::class, GD_EM_Module_Processor_Maps::MODULE_EM_MAP_POST];
            $ret[] = $map_inner_module;
        }

        return $ret;
    }

    protected function getPostmapInnerModule(array $module)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDARMAP => [PoP_Events_Locations_Module_Processor_Calendars::class, PoP_Events_Locations_Module_Processor_Calendars::MODULE_CALENDAR_EVENTSMAP],
            self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDARMAP => [PoP_Events_Locations_Module_Processor_Calendars::class, PoP_Events_Locations_Module_Processor_Calendars::MODULE_CALENDAR_EVENTSMAP],
            self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDARMAP => [PoP_Events_Locations_Module_Processor_Calendars::class, PoP_Events_Locations_Module_Processor_Calendars::MODULE_CALENDAR_EVENTSMAP],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(

        /*********************************************
         * Typeaheads
         *********************************************/
            // Straight to the layout
            self::MODULE_DATALOAD_EVENTS_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_PASTEVENTS_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],

        /*********************************************
         * Scrolls
         *********************************************/

        /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
        * Common blocks (Home/Page/Author/Single)
        *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::MODULE_DATALOAD_PASTEVENTS_SCROLL_NAVIGATOR => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_NAVIGATOR],
            self::MODULE_DATALOAD_EVENTS_SCROLL_NAVIGATOR => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_NAVIGATOR],

            self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_NAVIGATOR => [GD_EM_Module_Processor_Calendars::class, GD_EM_Module_Processor_Calendars::MODULE_CALENDAR_EVENTS_NAVIGATOR],

            self::MODULE_DATALOAD_PASTEVENTS_SCROLL_ADDONS => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_ADDONS],
            self::MODULE_DATALOAD_EVENTS_SCROLL_ADDONS => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_ADDONS],

            self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_ADDONS => [GD_EM_Module_Processor_Calendars::class, GD_EM_Module_Processor_Calendars::MODULE_CALENDAR_EVENTS_ADDONS],

        /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
        * Home/Page blocks
        *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::MODULE_DATALOAD_EVENTS_SCROLL_DETAILS => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_DETAILS],
            self::MODULE_DATALOAD_PASTEVENTS_SCROLL_DETAILS => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_DETAILS],

            self::MODULE_DATALOAD_EVENTS_SCROLL_SIMPLEVIEW => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_SIMPLEVIEW],
            self::MODULE_DATALOAD_PASTEVENTS_SCROLL_SIMPLEVIEW => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_SIMPLEVIEW],

            self::MODULE_DATALOAD_EVENTS_SCROLL_FULLVIEW => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_FULLVIEW],
            self::MODULE_DATALOAD_PASTEVENTS_SCROLL_FULLVIEW => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_FULLVIEW],

            self::MODULE_DATALOAD_EVENTS_SCROLL_THUMBNAIL => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_THUMBNAIL],
            self::MODULE_DATALOAD_PASTEVENTS_SCROLL_THUMBNAIL => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_THUMBNAIL],

            self::MODULE_DATALOAD_EVENTS_SCROLL_LIST => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_LIST],
            self::MODULE_DATALOAD_PASTEVENTS_SCROLL_LIST => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_LIST],

            self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR => [GD_EM_Module_Processor_Calendars::class, GD_EM_Module_Processor_Calendars::MODULE_CALENDAR_EVENTS_MAIN],

        /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
        * Author blocks
        *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_DETAILS => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_DETAILS],

            self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_SIMPLEVIEW],//[self::class, self::MODULE_SCROLL_AUTHOREVENTS_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_SIMPLEVIEW],//[self::class, self::MODULE_SCROLL_AUTHORPASTEVENTS_SIMPLEVIEW],

            self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_FULLVIEW => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHOREVENTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPASTEVENTS_FULLVIEW],

            self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_THUMBNAIL => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_THUMBNAIL],

            self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_LIST => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_LIST],
            self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_LIST],

            self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDAR => [GD_EM_Module_Processor_Calendars::class, GD_EM_Module_Processor_Calendars::MODULE_CALENDAR_EVENTS_MAIN],

        /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
        * Tag blocks
        *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::MODULE_DATALOAD_TAGEVENTS_SCROLL_DETAILS => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_DETAILS],
            self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_DETAILS => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_DETAILS],

            self::MODULE_DATALOAD_TAGEVENTS_SCROLL_SIMPLEVIEW => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_SIMPLEVIEW],//[self::class, self::MODULE_SCROLL_AUTHOREVENTS_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_SIMPLEVIEW => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_SIMPLEVIEW],//[self::class, self::MODULE_SCROLL_AUTHORPASTEVENTS_SIMPLEVIEW],

            self::MODULE_DATALOAD_TAGEVENTS_SCROLL_FULLVIEW => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_FULLVIEW => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_FULLVIEW],

            self::MODULE_DATALOAD_TAGEVENTS_SCROLL_THUMBNAIL => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_THUMBNAIL => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_THUMBNAIL],

            self::MODULE_DATALOAD_TAGEVENTS_SCROLL_LIST => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_EVENTS_LIST],
            self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_LIST => [PoP_Events_Module_Processor_CustomScrolls::class, PoP_Events_Module_Processor_CustomScrolls::MODULE_SCROLL_PASTEVENTS_LIST],

            self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDAR => [GD_EM_Module_Processor_Calendars::class, GD_EM_Module_Processor_Calendars::MODULE_CALENDAR_EVENTS_MAIN],

        /*********************************************
         * Post Carousels
         *********************************************/

            self::MODULE_DATALOAD_EVENTS_CAROUSEL => [GD_EM_Module_Processor_CustomCarousels::class, GD_EM_Module_Processor_CustomCarousels::MODULE_CAROUSEL_EVENTS],
            self::MODULE_DATALOAD_AUTHOREVENTS_CAROUSEL => [GD_EM_Module_Processor_CustomCarousels::class, GD_EM_Module_Processor_CustomCarousels::MODULE_CAROUSEL_AUTHOREVENTS],
            self::MODULE_DATALOAD_TAGEVENTS_CAROUSEL => [GD_EM_Module_Processor_CustomCarousels::class, GD_EM_Module_Processor_CustomCarousels::MODULE_CAROUSEL_TAGEVENTS],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    protected function getFeedbackmessagesPosition(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR:
            case self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDAR:
            case self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDAR:
            case self::MODULE_DATALOAD_EVENTS_TYPEAHEAD:
            case self::MODULE_DATALOAD_PASTEVENTS_TYPEAHEAD:
            case self::MODULE_DATALOAD_EVENTS_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHOREVENTS_CAROUSEL:
            case self::MODULE_DATALOAD_TAGEVENTS_CAROUSEL:
                return 'top';
        }

        return parent::getFeedbackmessagesPosition($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_EVENTS_TYPEAHEAD:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_LIST:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_EVENTS];

            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_AUTHOREVENTS];

            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_LIST:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_TAGEVENTS];

            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_EVENTSCALENDAR];

            case self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDAR:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_AUTHOREVENTSCALENDAR];

            case self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDAR:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_TAGEVENTSCALENDAR];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {

        // Add the format attr
        $details = array(
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_DETAILS],

            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS],

            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_DETAILS],
        );
        $simpleviews = array(
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_SIMPLEVIEW],

            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW],

            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_FULLVIEW],

            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW],

            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_THUMBNAIL],

            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL],

            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_LIST],
        );
        $calendarmaps = array(
            [self::class, self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDARMAP],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDARMAP],
            [self::class, self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDARMAP],
        );
        $calendars = array(
            [self::class, self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDAR],
            [self::class, self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDAR],
        );
        $typeaheads = array(
            [self::class, self::MODULE_DATALOAD_EVENTS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_TYPEAHEAD],
        );
        $carousels = array(
            [self::class, self::MODULE_DATALOAD_EVENTS_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_CAROUSEL],
        );
        if (in_array($module, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($module, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        } elseif (in_array($module, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($module, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($module, $lists)) {
            $format = POP_FORMAT_LIST;
        } elseif (in_array($module, $calendarmaps)) {
            $format = POP_FORMAT_CALENDARMAP;
            // $format = POP_FORMAT_MAP;
        } elseif (in_array($module, $calendars)) {
            $format = POP_FORMAT_CALENDAR;
        } elseif (in_array($module, $typeaheads)) {
            $format = POP_FORMAT_TYPEAHEAD;
        } elseif (in_array($module, $carousels)) {
            $format = POP_FORMAT_CAROUSEL;
        }

        return $format ?? parent::getFormat($module);
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDARMAP:
    //         case self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDAR:
    //         case self::MODULE_DATALOAD_AUTHOREVENTS_CAROUSEL:
    //             return UserRequestNature::USER;

    //         case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDARMAP:
    //         case self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDAR:
    //         case self::MODULE_DATALOAD_TAGEVENTS_CAROUSEL:
    //             return TagRequestNature::TAG;
    //     }

    //     return parent::getNature($module);
    // }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
         // Filter by the Profile/Community
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDAR:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHOREVENTS_CAROUSEL:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorcontent($ret);
                break;

            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDAR:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGEVENTS_CAROUSEL:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagcontent($ret);
                break;
        }

        return $ret;
    }

    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_NAVIGATOR:
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_ADDONS:
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR:
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDAR:
            case self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDAR:
            case self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDARMAP:
                return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_Calendar::class);
        }

        return parent::getQueryInputOutputHandler($module);
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_PASTEVENTS_TYPEAHEAD:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_LIST:
                $this->addPastEventImmutableDataloadQueryArgs($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_EVENTS_TYPEAHEAD:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDAR:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDAR:
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_NAVIGATOR:
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_ADDONS:
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR:
            case self::MODULE_DATALOAD_EVENTS_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHOREVENTS_CAROUSEL:
            case self::MODULE_DATALOAD_TAGEVENTS_CAROUSEL:
            case self::MODULE_DATALOAD_PASTEVENTS_TYPEAHEAD:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_LIST:
                return $this->instanceManager->getInstance(EventObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_EVENTS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_EVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_NAVIGATOR:
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR_ADDONS:
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_AUTHOREVENTSCALENDAR_CALENDAR:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDARMAP:
            case self::MODULE_DATALOAD_TAGEVENTSCALENDAR_CALENDAR:
            case self::MODULE_DATALOAD_EVENTS_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHOREVENTS_CAROUSEL:
            case self::MODULE_DATALOAD_TAGEVENTS_CAROUSEL:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('events', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('past events', 'poptheme-wassup'));
                break;
        }

        // Events: choose to only select past/future
        $past = array(
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLL_LIST],
        );
        $future = array(
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLL_LIST],
        );
        if (in_array($module, $past)) {
            $daterange_class = 'daterange-past opens-right';
        } elseif (in_array($module, $future)) {
            $daterange_class = 'daterange-future opens-right';
        }
        if ($daterange_class) {
            $this->setProp([PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_EVENTSCOPE], $props, 'daterange-class', $daterange_class);
        }

        parent::initModelProps($module, $props);
    }
}



