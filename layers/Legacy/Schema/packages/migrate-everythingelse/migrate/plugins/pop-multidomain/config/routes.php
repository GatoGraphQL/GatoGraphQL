<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_MULTIDOMAIN_ROUTE_EXTERNAL')) {
    define('POP_MULTIDOMAIN_ROUTE_EXTERNAL', $definitionManager->getUniqueDefinition('external', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_MULTIDOMAIN_ROUTE_EXTERNAL,
    		]
    	);
    }
);
