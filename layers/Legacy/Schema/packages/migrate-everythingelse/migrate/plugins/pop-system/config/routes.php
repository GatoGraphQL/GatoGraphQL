<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
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

\PoP\Root\App::getHookManager()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
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
