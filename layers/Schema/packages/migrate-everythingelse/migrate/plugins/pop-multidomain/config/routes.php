<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_MULTIDOMAIN_ROUTE_EXTERNAL')) {
    define('POP_MULTIDOMAIN_ROUTE_EXTERNAL', $definitionManager->getUniqueDefinition('external', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_MULTIDOMAIN_ROUTE_EXTERNAL,
    		]
    	);
    }
);