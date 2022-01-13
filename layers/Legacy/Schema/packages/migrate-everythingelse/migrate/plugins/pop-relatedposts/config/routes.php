<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT')) {
	define('POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT', $definitionManager->getUniqueDefinition('related-content', DefinitionGroups::ROUTES));
}

\PoP\Root\App::getHookManager()->addFilter(
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
