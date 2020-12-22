<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_LOCATIONS_ROUTE_LOCATIONS')) {
    define('POP_LOCATIONS_ROUTE_LOCATIONS', $definitionManager->getUniqueDefinition('locations', DefinitionGroups::ROUTES));
}
if (!defined('POP_LOCATIONS_ROUTE_LOCATIONSMAP')) {
    define('POP_LOCATIONS_ROUTE_LOCATIONSMAP', $definitionManager->getUniqueDefinition('locations-map', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_LOCATIONS_ROUTE_LOCATIONS,
				POP_LOCATIONS_ROUTE_LOCATIONSMAP,
    		]
    	);
    }
);