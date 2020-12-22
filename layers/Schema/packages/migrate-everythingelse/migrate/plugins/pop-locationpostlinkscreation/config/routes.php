<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK')) {
    define('POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK', $definitionManager->getUniqueDefinition('add-locationpostlink', DefinitionGroups::ROUTES));
}
if (!defined('POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK')) {
    define('POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK', $definitionManager->getUniqueDefinition('edit-locationpostlink', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
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