<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// System Pages
//--------------------------------------------------------
if (!defined('POP_SYSTEMWP_ROUTE_SYSTEM_ACTIVATEPLUGINS')) {
	define('POP_SYSTEMWP_ROUTE_SYSTEM_ACTIVATEPLUGINS', $definitionManager->getUniqueDefinition('system/activate-plugins', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
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
