<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

class Wassup_EM_BP_SubmenuHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_CustomSubMenus:author:routes',
            array($this, 'addRoutes')
        );
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_CustomSubMenus:tag:routes',
            array($this, 'addRoutes')
        );
    }

    public function addRoutes($routes)
    {

        // Events
        if (defined('POP_EVENTS_ROUTE_EVENTS') && POP_EVENTS_ROUTE_EVENTS) {
            $event_subheaders = array(
                POP_EVENTS_ROUTE_EVENTSCALENDAR,
                POP_EVENTS_ROUTE_PASTEVENTS,
            );
            $routes[POP_EVENTS_ROUTE_EVENTS] = array_merge(
                array(
                    POP_EVENTS_ROUTE_EVENTS,
                ),
                $event_subheaders
            );
            $vars = ApplicationState::getVars();
            $route = \PoP\Root\App::getState('route');
            if (in_array($route, $event_subheaders)) {
                $routes[$route] = array();
            }
        }
        
        return $routes;
    }
}

/**
 * Initialization
 */
new Wassup_EM_BP_SubmenuHooks();
