<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_ADDLOCATIONS_ROUTE_ADDLOCATION')) {
	define('POP_ADDLOCATIONS_ROUTE_ADDLOCATION', $definitionManager->getUniqueDefinition('add-location', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_ADDLOCATIONS_ROUTE_ADDLOCATION,
    		]
    	);
    }
);
