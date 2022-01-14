<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_USERLOGIN_ROUTE_LOGIN')) {
    define('POP_USERLOGIN_ROUTE_LOGIN', $definitionManager->getUniqueDefinition('login', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERLOGIN_ROUTE_LOSTPWD')) {
    define('POP_USERLOGIN_ROUTE_LOSTPWD', $definitionManager->getUniqueDefinition('lost-password', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERLOGIN_ROUTE_LOSTPWDRESET')) {
    define('POP_USERLOGIN_ROUTE_LOSTPWDRESET', $definitionManager->getUniqueDefinition('lost-password-reset', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERLOGIN_ROUTE_LOGOUT')) {
    define('POP_USERLOGIN_ROUTE_LOGOUT', $definitionManager->getUniqueDefinition('logout', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA')) {
    define('POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA', $definitionManager->getUniqueDefinition('loggedinuser-data', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\RoutingWP\HookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_USERLOGIN_ROUTE_LOGIN,
				POP_USERLOGIN_ROUTE_LOSTPWD,
				POP_USERLOGIN_ROUTE_LOSTPWDRESET,
				POP_USERLOGIN_ROUTE_LOGOUT,
				POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA,
    		]
    	);
    }
);
