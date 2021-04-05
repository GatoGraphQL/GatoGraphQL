<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_EVENTS_ROUTE_EVENTS')) {
    define('POP_EVENTS_ROUTE_EVENTS', $definitionManager->getUniqueDefinition('events', DefinitionGroups::ROUTES));
}
if (!defined('POP_EVENTS_ROUTE_EVENTSCALENDAR')) {
    define('POP_EVENTS_ROUTE_EVENTSCALENDAR', $definitionManager->getUniqueDefinition('calendar', DefinitionGroups::ROUTES));
}
if (!defined('POP_EVENTS_ROUTE_PASTEVENTS')) {
    define('POP_EVENTS_ROUTE_PASTEVENTS', $definitionManager->getUniqueDefinition('past-events', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_EVENTS_ROUTE_EVENTS,
				POP_EVENTS_ROUTE_EVENTSCALENDAR,
				POP_EVENTS_ROUTE_PASTEVENTS,
    		]
    	);
    }
);
