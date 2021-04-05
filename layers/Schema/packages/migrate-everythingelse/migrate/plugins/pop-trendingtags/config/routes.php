<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS')) {
    define('POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS', $definitionManager->getUniqueDefinition('trending-tags', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS,
    		]
    	);
    }
);
