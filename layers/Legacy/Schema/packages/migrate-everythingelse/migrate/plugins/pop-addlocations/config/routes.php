<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_ADDLOCATIONS_ROUTE_ADDLOCATION')) {
	define('POP_ADDLOCATIONS_ROUTE_ADDLOCATION', $definitionManager->getUniqueDefinition('add-location', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_ADDLOCATIONS_ROUTE_ADDLOCATION,
    		]
    	);
    }
);
