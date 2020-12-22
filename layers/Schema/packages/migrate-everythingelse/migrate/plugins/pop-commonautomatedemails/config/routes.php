<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY')) {
    define('POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY', $definitionManager->getUniqueDefinition('latest-content-weekly', DefinitionGroups::ROUTES));
}
if (!defined('POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL')) {
    define('POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL', $definitionManager->getUniqueDefinition('single-post-special', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY,
				POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL,
    		]
    	);
    }
);