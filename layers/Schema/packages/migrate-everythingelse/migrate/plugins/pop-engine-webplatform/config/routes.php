<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL')) {
	define('POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL', $definitionManager->getUniqueDefinition('loaders/appshell', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL,
    		]
    	);
    }
);
