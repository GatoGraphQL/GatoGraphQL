<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Loader Pages
//--------------------------------------------------------
if (!defined('POP_POSTTAGS_ROUTE_POSTTAGS')) {
    define('POP_POSTTAGS_ROUTE_POSTTAGS', $definitionManager->getUniqueDefinition('post-tags', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_POSTTAGS_ROUTE_POSTTAGS,
    		]
    	);
    }
);
