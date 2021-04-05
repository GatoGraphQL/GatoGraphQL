<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_ADDCOMMENTS_ROUTE_ADDCOMMENT')) {
	define('POP_ADDCOMMENTS_ROUTE_ADDCOMMENT', $definitionManager->getUniqueDefinition('add-comment', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_ADDCOMMENTS_ROUTE_ADDCOMMENT,
    		]
    	);
    }
);
