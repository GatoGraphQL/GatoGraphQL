<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK')) {
    define('POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK', $definitionManager->getUniqueDefinition('add-eventlink', DefinitionGroups::ROUTES));
}
if (!defined('POP_EVENTLINKSCREATION_ROUTE_EDITEVENTLINK')) {
    define('POP_EVENTLINKSCREATION_ROUTE_EDITEVENTLINK', $definitionManager->getUniqueDefinition('edit-eventlink', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK,
				POP_EVENTLINKSCREATION_ROUTE_EDITEVENTLINK,
    		]
    	);
    }
);