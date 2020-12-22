<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Loader Pages
//--------------------------------------------------------
if (!defined('POP_POSTS_ROUTE_POSTS')) {
    define('POP_POSTS_ROUTE_POSTS', $definitionManager->getUniqueDefinition('posts', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
                POP_POSTS_ROUTE_POSTS,
    		]
    	);
    }
);
