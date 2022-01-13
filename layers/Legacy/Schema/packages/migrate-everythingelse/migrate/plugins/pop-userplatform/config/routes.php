<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_USERPLATFORM_ROUTE_INVITENEWUSERS')) {
    define('POP_USERPLATFORM_ROUTE_INVITENEWUSERS', $definitionManager->getUniqueDefinition('invite-new-users', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERPLATFORM_ROUTE_SETTINGS')) {
    define('POP_USERPLATFORM_ROUTE_SETTINGS', $definitionManager->getUniqueDefinition('settings', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERPLATFORM_ROUTE_MYPROFILE')) {
    define('POP_USERPLATFORM_ROUTE_MYPROFILE', $definitionManager->getUniqueDefinition('my-profile', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERPLATFORM_ROUTE_MYPREFERENCES')) {
    define('POP_USERPLATFORM_ROUTE_MYPREFERENCES', $definitionManager->getUniqueDefinition('my-preferences', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERPLATFORM_ROUTE_ADDPROFILE')) {
    define('POP_USERPLATFORM_ROUTE_ADDPROFILE', $definitionManager->getUniqueDefinition('add-profile', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERPLATFORM_ROUTE_EDITPROFILE')) {
    define('POP_USERPLATFORM_ROUTE_EDITPROFILE', $definitionManager->getUniqueDefinition('edit-profile', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE')) {
    define('POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE', $definitionManager->getUniqueDefinition('change-password', DefinitionGroups::ROUTES));
}

\PoP\Root\App::getHookManager()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_USERPLATFORM_ROUTE_INVITENEWUSERS,
				POP_USERPLATFORM_ROUTE_SETTINGS,
				POP_USERPLATFORM_ROUTE_MYPROFILE,
				POP_USERPLATFORM_ROUTE_MYPREFERENCES,
				POP_USERPLATFORM_ROUTE_ADDPROFILE,
				POP_USERPLATFORM_ROUTE_EDITPROFILE,
				POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE,
    		]
    	);
    }
);
