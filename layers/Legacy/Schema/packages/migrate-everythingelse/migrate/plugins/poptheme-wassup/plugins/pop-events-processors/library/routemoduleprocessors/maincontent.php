<?php

use PoP\Routing\RouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class PoP_Events_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);
        $default_format_sectioncalendar = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTIONCALENDAR);
        $default_format_mycontent = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);

        $routemodules_typeahead = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_TYPEAHEAD],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_TYPEAHEAD],
        );
        foreach ($routemodules_typeahead as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_section == POP_FORMAT_TYPEAHEAD) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_details = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTS_SCROLL_DETAILS],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_PASTEVENTS_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_simpleview = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTS_SCROLL_SIMPLEVIEW],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_PASTEVENTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_fullview = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTS_SCROLL_FULLVIEW],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_PASTEVENTS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTS_SCROLL_THUMBNAIL],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_PASTEVENTS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_list = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTS_SCROLL_LIST],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_PASTEVENTS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_map = array(
            POP_EVENTS_ROUTE_EVENTS => [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_EVENTS_SCROLLMAP],
            POP_EVENTS_ROUTE_PASTEVENTS => [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_PASTEVENTS_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_MAP) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_horizontalmap = array(
            POP_EVENTS_ROUTE_EVENTS => [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_EVENTS_HORIZONTALSCROLLMAP],
        );
        foreach ($routemodules_horizontalmap as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_HORIZONTALMAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_HORIZONTALMAP) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_navigator = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTS_SCROLL_NAVIGATOR],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_PASTEVENTS_SCROLL_NAVIGATOR],
        );
        foreach ($routemodules_navigator as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_NAVIGATOR,
                ],
            ];
            if ($default_format_section == POP_FORMAT_NAVIGATOR) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }

            // Navigator special case: use the NAVIGATOR module when the target is the navigator
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'target' => POP_TARGET_NAVIGATOR,
                ],
            ];
        }
        $routemodules_addons = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTS_SCROLL_ADDONS],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_PASTEVENTS_SCROLL_ADDONS],
        );
        foreach ($routemodules_addons as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_ADDONS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_ADDONS) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_carousels = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTS_CAROUSEL],
        );
        foreach ($routemodules_carousels as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_CAROUSEL) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }

        $routemodules_calendar = array(
            POP_EVENTS_ROUTE_EVENTSCALENDAR => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR],
        );
        foreach ($routemodules_calendar as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CALENDAR,
                ],
            ];
            if ($default_format_sectioncalendar == POP_FORMAT_CALENDAR) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_calendar_map = array(
            POP_EVENTS_ROUTE_EVENTSCALENDAR => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTSCALENDAR_CALENDARMAP],
        );
        foreach ($routemodules_calendar_map as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CALENDARMAP,
                ],
            ];
            if ($default_format_sectioncalendar == POP_FORMAT_CALENDARMAP) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_navigator = array(
            POP_EVENTS_ROUTE_EVENTSCALENDAR => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR_NAVIGATOR],
        );
        foreach ($routemodules_navigator as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_NAVIGATOR,
                ],
            ];
            if ($default_format_sectioncalendar == POP_FORMAT_NAVIGATOR) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }

            // Navigator special case: use the NAVIGATOR module when the target is the navigator
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'target' => POP_TARGET_NAVIGATOR,
                ],
            ];
        }
        $routemodules_addons = array(
            POP_EVENTS_ROUTE_EVENTSCALENDAR => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR_ADDONS],
        );
        foreach ($routemodules_addons as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_ADDONS,
                ],
            ];
            if ($default_format_sectioncalendar == POP_FORMAT_ADDONS) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }

        // Author route modules
        $default_format_authorsection = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORSECTION);
        $default_format_authorsectioncalendar = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORSECTIONCALENDAR);

        $routemodules_details = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHOREVENTS_SCROLL_DETAILS],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_DETAILS) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_simpleview = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHOREVENTS_SCROLL_SIMPLEVIEW],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_SIMPLEVIEW) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_fullview = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHOREVENTS_SCROLL_FULLVIEW],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_FULLVIEW) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHOREVENTS_SCROLL_THUMBNAIL],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_THUMBNAIL) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_list = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHOREVENTS_SCROLL_LIST],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_LIST) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_map = array(
            POP_EVENTS_ROUTE_EVENTS => [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_AUTHOREVENTS_SCROLLMAP],
            POP_EVENTS_ROUTE_PASTEVENTS => [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_AUTHORPASTEVENTS_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_MAP) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_horizontalmap = array(
            POP_EVENTS_ROUTE_EVENTS => [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP],
        );
        foreach ($routemodules_horizontalmap as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_HORIZONTALMAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_HORIZONTALMAP) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_carousels = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHOREVENTS_CAROUSEL],
        );
        foreach ($routemodules_carousels as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_CAROUSEL) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }

        $routemodules_calendar = array(
            POP_EVENTS_ROUTE_EVENTSCALENDAR => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHOREVENTSCALENDAR_CALENDAR],
        );
        foreach ($routemodules_calendar as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CALENDAR,
                ],
            ];
            if ($default_format_authorsectioncalendar == POP_FORMAT_CALENDAR) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_calendarmap = array(
            POP_EVENTS_ROUTE_EVENTSCALENDAR => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHOREVENTSCALENDAR_CALENDARMAP],
        );
        foreach ($routemodules_calendarmap as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CALENDARMAP,
                ],
            ];
            if ($default_format_authorsectioncalendar == POP_FORMAT_CALENDARMAP) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }

        // Tag route modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGSECTION);
        $default_format_sectioncalendar = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGSECTIONCALENDAR);

        $routemodules_details = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGEVENTS_SCROLL_DETAILS],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_simpleview = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGEVENTS_SCROLL_SIMPLEVIEW],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_fullview = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGEVENTS_SCROLL_FULLVIEW],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGEVENTS_SCROLL_THUMBNAIL],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_list = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGEVENTS_SCROLL_LIST],
            POP_EVENTS_ROUTE_PASTEVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGPASTEVENTS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_map = array(
            POP_EVENTS_ROUTE_EVENTS => [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_TAGEVENTS_SCROLLMAP],
            POP_EVENTS_ROUTE_PASTEVENTS => [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_TAGPASTEVENTS_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_MAP) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_horizontalmap = array(
            POP_EVENTS_ROUTE_EVENTS => [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP],
        );
        foreach ($routemodules_horizontalmap as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_HORIZONTALMAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_HORIZONTALMAP) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }

        $routemodules_carousels = array(
            POP_EVENTS_ROUTE_EVENTS => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGEVENTS_CAROUSEL],
        );
        foreach ($routemodules_carousels as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_CAROUSEL) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }

        $routemodules_calendar = array(
            POP_EVENTS_ROUTE_EVENTSCALENDAR => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGEVENTSCALENDAR_CALENDAR],
        );
        foreach ($routemodules_calendar as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CALENDAR,
                ],
            ];
            if ($default_format_sectioncalendar == POP_FORMAT_CALENDAR) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_calendarmap = array(
            POP_EVENTS_ROUTE_EVENTSCALENDAR => [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGEVENTSCALENDAR_CALENDARMAP],
        );
        foreach ($routemodules_calendarmap as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CALENDARMAP,
                ],
            ];
            if ($default_format_sectioncalendar == POP_FORMAT_CALENDARMAP) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_Events_Module_MainContentRouteModuleProcessor()
	);
}, 200);
