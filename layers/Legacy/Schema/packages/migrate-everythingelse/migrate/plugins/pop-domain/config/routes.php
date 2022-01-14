<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN')) {
    define('POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN', $definitionManager->getUniqueDefinition('loaders/initialize-domain', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\RoutingWP\HookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN,
    		]
    	);
    }
);
