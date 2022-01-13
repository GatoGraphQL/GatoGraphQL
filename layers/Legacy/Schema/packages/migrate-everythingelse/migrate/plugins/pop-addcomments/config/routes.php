<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
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
