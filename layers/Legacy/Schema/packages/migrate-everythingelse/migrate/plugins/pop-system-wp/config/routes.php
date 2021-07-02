<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// System Pages
//--------------------------------------------------------
if (!defined('POP_SYSTEMWP_ROUTE_SYSTEM_ACTIVATEPLUGINS')) {
	define('POP_SYSTEMWP_ROUTE_SYSTEM_ACTIVATEPLUGINS', $definitionManager->getUniqueDefinition('system/activate-plugins', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
                POP_SYSTEMWP_ROUTE_SYSTEM_ACTIVATEPLUGINS,
    		]
    	);
    }
);
