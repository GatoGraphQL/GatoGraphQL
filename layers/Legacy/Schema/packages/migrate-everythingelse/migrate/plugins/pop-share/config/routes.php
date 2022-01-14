<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_SHARE_ROUTE_SHAREBYEMAIL')) {
	define('POP_SHARE_ROUTE_SHAREBYEMAIL', $definitionManager->getUniqueDefinition('share-by-email', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\RoutingWP\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_SHARE_ROUTE_SHAREBYEMAIL,
    		]
    	);
    }
);
