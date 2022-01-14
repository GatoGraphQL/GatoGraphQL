<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY')) {
    define('POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY', $definitionManager->getUniqueDefinition('latest-content-weekly', DefinitionGroups::ROUTES));
}
if (!defined('POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL')) {
    define('POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL', $definitionManager->getUniqueDefinition('single-post-special', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\Root\Routing\RouteHookNames::ROUTES,
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
