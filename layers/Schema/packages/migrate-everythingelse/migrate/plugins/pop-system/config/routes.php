<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// System Pages
//--------------------------------------------------------
if (!defined('POP_SYSTEM_ROUTE_SYSTEM_BUILD')) {
    define('POP_SYSTEM_ROUTE_SYSTEM_BUILD', $definitionManager->getUniqueDefinition('system/build', DefinitionGroups::ROUTES));
}
if (!defined('POP_SYSTEM_ROUTE_SYSTEM_GENERATE')) {
    define('POP_SYSTEM_ROUTE_SYSTEM_GENERATE', $definitionManager->getUniqueDefinition('system/generate', DefinitionGroups::ROUTES));
}
if (!defined('POP_SYSTEM_ROUTE_SYSTEM_INSTALL')) {
    define('POP_SYSTEM_ROUTE_SYSTEM_INSTALL', $definitionManager->getUniqueDefinition('system/install', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_SYSTEM_ROUTE_SYSTEM_BUILD,
				POP_SYSTEM_ROUTE_SYSTEM_GENERATE,
				POP_SYSTEM_ROUTE_SYSTEM_INSTALL,
    		]
    	);
    }
);