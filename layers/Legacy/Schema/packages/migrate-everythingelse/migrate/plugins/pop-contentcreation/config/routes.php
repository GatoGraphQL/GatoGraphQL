<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_CONTENTCREATION_ROUTE_ADDCONTENT')) {
    define('POP_CONTENTCREATION_ROUTE_ADDCONTENT', $definitionManager->getUniqueDefinition('add-content', DefinitionGroups::ROUTES));
}
if (!defined('POP_CONTENTCREATION_ROUTE_MYCONTENT')) {
    define('POP_CONTENTCREATION_ROUTE_MYCONTENT', $definitionManager->getUniqueDefinition('my-content', DefinitionGroups::ROUTES));
}
if (!defined('POP_CONTENTCREATION_ROUTE_FLAG')) {
    define('POP_CONTENTCREATION_ROUTE_FLAG', $definitionManager->getUniqueDefinition('flag', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_CONTENTCREATION_ROUTE_ADDCONTENT,
				POP_CONTENTCREATION_ROUTE_MYCONTENT,
				POP_CONTENTCREATION_ROUTE_FLAG,
    		]
    	);
    }
);
