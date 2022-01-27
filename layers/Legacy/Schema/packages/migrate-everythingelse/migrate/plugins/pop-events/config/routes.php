<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
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

\PoP\Root\App::addFilter(
    \PoP\RootWP\Routing\HookNames::ROUTES,
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
