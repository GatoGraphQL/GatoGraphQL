<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Loader Pages
//--------------------------------------------------------
if (!defined('POP_CATEGORIES_ROUTE_CATEGORIES')) {
    define('POP_CATEGORIES_ROUTE_CATEGORIES', $definitionManager->getUniqueDefinition('categories', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_CATEGORIES_ROUTE_CATEGORIES,
    		]
    	);
    }
);
