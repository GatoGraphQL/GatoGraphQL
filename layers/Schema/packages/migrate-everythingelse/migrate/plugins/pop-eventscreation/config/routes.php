<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
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

HooksAPIFacade::getInstance()->addFilter(
    'routes',
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