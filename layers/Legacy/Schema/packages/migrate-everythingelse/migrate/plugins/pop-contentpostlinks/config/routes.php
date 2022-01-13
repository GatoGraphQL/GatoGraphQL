<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS')) {
	define('POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS', $definitionManager->getUniqueDefinition('postlinks', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
    		]
    	);
    }
);
