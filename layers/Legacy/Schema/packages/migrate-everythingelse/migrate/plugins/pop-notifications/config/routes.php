<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS')) {
    define('POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS', $definitionManager->getUniqueDefinition('notifications', DefinitionGroups::ROUTES));
}
if (!defined('POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD')) {
    define('POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD', $definitionManager->getUniqueDefinition('notifications/mark-all-as-read', DefinitionGroups::ROUTES));
}
if (!defined('POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD')) {
    define('POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD', $definitionManager->getUniqueDefinition('notifications/mark-as-read', DefinitionGroups::ROUTES));
}
if (!defined('POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD')) {
    define('POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD', $definitionManager->getUniqueDefinition('notifications/mark-as-unread', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\RootWP\Routing\HookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS,
				POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD,
				POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD,
				POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD,
    		]
    	);
    }
);
