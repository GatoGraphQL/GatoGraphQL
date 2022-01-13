<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
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
