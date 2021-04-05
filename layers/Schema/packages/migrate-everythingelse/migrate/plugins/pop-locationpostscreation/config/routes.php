<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS')) {
    define('POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS', $definitionManager->getUniqueDefinition('my-locationposts', DefinitionGroups::ROUTES));
}
if (!defined('POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST')) {
    define('POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST', $definitionManager->getUniqueDefinition('add-locationpost', DefinitionGroups::ROUTES));
}
if (!defined('POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST')) {
    define('POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST', $definitionManager->getUniqueDefinition('edit-locationpost', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
				POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST,
				POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST,
    		]
    	);
    }
);
