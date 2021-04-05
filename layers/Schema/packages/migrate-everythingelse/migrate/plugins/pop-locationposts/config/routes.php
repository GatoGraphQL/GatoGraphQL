<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS')) {
	define('POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS', $definitionManager->getUniqueDefinition('locationposts', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
    		]
    	);
    }
);
