<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN')) {
    define('POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN', $definitionManager->getUniqueDefinition('loaders/initialize-domain', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN,
    		]
    	);
    }
);