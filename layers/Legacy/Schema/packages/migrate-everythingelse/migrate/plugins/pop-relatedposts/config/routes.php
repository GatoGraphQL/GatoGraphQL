<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT')) {
	define('POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT', $definitionManager->getUniqueDefinition('related-content', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\RoutingWP\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
    		]
    	);
    }
);
