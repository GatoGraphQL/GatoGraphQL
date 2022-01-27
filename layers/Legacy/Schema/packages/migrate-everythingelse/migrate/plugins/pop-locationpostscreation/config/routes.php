<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
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

\PoP\Root\App::addFilter(
    \PoP\RootWP\Routing\HookNames::ROUTES,
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
