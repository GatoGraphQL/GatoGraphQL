<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS')) {
	define('POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS', $definitionManager->getUniqueDefinition('locationposts', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\RoutingWP\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
    		]
    	);
    }
);
