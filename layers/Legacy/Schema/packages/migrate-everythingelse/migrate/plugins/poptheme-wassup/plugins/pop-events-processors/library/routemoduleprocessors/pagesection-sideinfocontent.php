<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Events\Facades\EventTypeAPIFacade;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_Events_Module_SideInfoContentPageSectionRouteModuleProcessor extends PoP_Module_SideInfoContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_EVENTS_ROUTE_EVENTS => [GD_EM_Module_Processor_SidebarMultiples::class, GD_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHOREVENTS_SIDEBAR],
            POP_EVENTS_ROUTE_PASTEVENTS => [GD_EM_Module_Processor_SidebarMultiples::class, GD_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHORPASTEVENTS_SIDEBAR],
            POP_EVENTS_ROUTE_EVENTSCALENDAR => [GD_EM_Module_Processor_SidebarMultiples::class, GD_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHOREVENTSCALENDAR_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = ['module' => $module];
        }

        $modules = array(
            POP_EVENTS_ROUTE_EVENTS => [GD_EM_Module_Processor_SidebarMultiples::class, GD_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_EVENTS_SIDEBAR],
            POP_EVENTS_ROUTE_PASTEVENTS => [GD_EM_Module_Processor_SidebarMultiples::class, GD_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_PASTEVENTS_SIDEBAR],
            POP_EVENTS_ROUTE_EVENTSCALENDAR => [GD_EM_Module_Processor_SidebarMultiples::class, GD_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_EVENTS_CALENDAR_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
        }

        $modules = array(
            POP_EVENTS_ROUTE_EVENTS => [GD_EM_Module_Processor_SidebarMultiples::class, GD_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_EVENTS_SIDEBAR],
            POP_EVENTS_ROUTE_PASTEVENTS => [GD_EM_Module_Processor_SidebarMultiples::class, GD_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_PASTEVENTS_SIDEBAR],
            POP_EVENTS_ROUTE_EVENTSCALENDAR => [GD_EM_Module_Processor_SidebarMultiples::class, GD_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_EVENTS_CALENDAR_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
        }

        return $ret;
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $eventTypeAPI = EventTypeAPIFacade::getInstance();

        // Past single event
        $ret[CustomPostRequestNature::CUSTOMPOST][] = [
            'module' => [GD_EM_Module_Processor_SidebarMultiples::class, GD_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_PASTEVENT_SIDEBAR],
            'conditions' => [
                'routing' => [
                    'queried-object-post-type' => $eventTypeAPI->getEventCustomPostType(),
                    'queried-object-is-past-event' => true,
                ],
            ],
        ];

        // Future and current single event
        $ret[CustomPostRequestNature::CUSTOMPOST][] = [
            'module' => [GD_EM_Module_Processor_SidebarMultiples::class, GD_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_EVENT_SIDEBAR],
            'conditions' => [
                'routing' => [
                    'queried-object-post-type' => $eventTypeAPI->getEventCustomPostType(),
                ],
            ],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_Events_Module_SideInfoContentPageSectionRouteModuleProcessor()
	);
}, 200);
