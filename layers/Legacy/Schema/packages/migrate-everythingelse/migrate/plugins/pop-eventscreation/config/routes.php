<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_EVENTSCREATION_ROUTE_MYEVENTS')) {
    define('POP_EVENTSCREATION_ROUTE_MYEVENTS', $definitionManager->getUniqueDefinition('my-events', DefinitionGroups::ROUTES));
}
if (!defined('POP_EVENTSCREATION_ROUTE_MYPASTEVENTS')) {
    define('POP_EVENTSCREATION_ROUTE_MYPASTEVENTS', $definitionManager->getUniqueDefinition('my-past-events', DefinitionGroups::ROUTES));
}
if (!defined('POP_EVENTSCREATION_ROUTE_ADDEVENT')) {
    define('POP_EVENTSCREATION_ROUTE_ADDEVENT', $definitionManager->getUniqueDefinition('add-event', DefinitionGroups::ROUTES));
}
if (!defined('POP_EVENTSCREATION_ROUTE_EDITEVENT')) {
    define('POP_EVENTSCREATION_ROUTE_EDITEVENT', $definitionManager->getUniqueDefinition('edit-event', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_EVENTSCREATION_ROUTE_MYEVENTS,
				POP_EVENTSCREATION_ROUTE_MYPASTEVENTS,
				POP_EVENTSCREATION_ROUTE_ADDEVENT,
				POP_EVENTSCREATION_ROUTE_EDITEVENT,
    		]
    	);
    }
);
