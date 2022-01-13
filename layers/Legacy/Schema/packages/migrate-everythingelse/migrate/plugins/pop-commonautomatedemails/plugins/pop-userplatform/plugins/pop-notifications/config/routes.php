<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY')) {
	define('POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY', $definitionManager->getUniqueDefinition('latest-notifications-daily', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY,
    		]
    	);
    }
);
