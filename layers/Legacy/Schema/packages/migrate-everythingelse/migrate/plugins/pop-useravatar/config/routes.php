<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_USERAVATAR_ROUTE_EDITAVATAR')) {
	define('POP_USERAVATAR_ROUTE_EDITAVATAR', $definitionManager->getUniqueDefinition('edit-avatar', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\RoutingWP\HookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_USERAVATAR_ROUTE_EDITAVATAR,
    		]
    	);
    }
);
