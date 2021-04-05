<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT')) {
	define('POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT', $definitionManager->getUniqueDefinition('related-content', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
    		]
    	);
    }
);
