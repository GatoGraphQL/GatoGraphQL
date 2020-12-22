<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Loader Pages
//--------------------------------------------------------
if (!defined('POP_USERS_ROUTE_USERS')) {
    define('POP_USERS_ROUTE_USERS', $definitionManager->getUniqueDefinition('users', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_USERS_ROUTE_USERS,
    		]
    	);
    }
);
