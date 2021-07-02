<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// System Pages
//--------------------------------------------------------
if (!defined('POP_SYSTEM_ROUTE_SYSTEM_GENERATETHEME')) {
	define('POP_SYSTEM_ROUTE_SYSTEM_GENERATETHEME', $definitionManager->getUniqueDefinition('system/generatetheme', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_SYSTEM_ROUTE_SYSTEM_GENERATETHEME,
    		]
    	);
    }
);
