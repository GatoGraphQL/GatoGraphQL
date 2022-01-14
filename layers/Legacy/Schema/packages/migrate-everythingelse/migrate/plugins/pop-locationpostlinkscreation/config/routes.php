<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK')) {
    define('POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK', $definitionManager->getUniqueDefinition('add-locationpostlink', DefinitionGroups::ROUTES));
}
if (!defined('POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK')) {
    define('POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK', $definitionManager->getUniqueDefinition('edit-locationpostlink', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\RoutingWP\HookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK,
				POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK,
    		]
    	);
    }
);
