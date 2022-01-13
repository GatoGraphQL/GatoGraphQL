<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
// if (!defined('POPTHEME_WASSUP_ROUTE_MAIN')) {
//     define('POPTHEME_WASSUP_ROUTE_MAIN', $definitionManager->getUniqueDefinition('main', DefinitionGroups::ROUTES));
// }
// if (!defined('POPTHEME_WASSUP_ROUTE_DESCRIPTION')) {
//     define('POPTHEME_WASSUP_ROUTE_DESCRIPTION', $definitionManager->getUniqueDefinition('description', DefinitionGroups::ROUTES));
// }
if (!defined('POPTHEME_WASSUP_ROUTE_SUMMARY')) {
    define('POPTHEME_WASSUP_ROUTE_SUMMARY', $definitionManager->getUniqueDefinition('summary', DefinitionGroups::ROUTES));
}
// if (!defined('POPTHEME_WASSUP_ROUTE_POSTAUTHORS')) {
//     define('POPTHEME_WASSUP_ROUTE_POSTAUTHORS', $definitionManager->getUniqueDefinition('post-authors', DefinitionGroups::ROUTES));
// }
if (!defined('POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES')) {
    define('POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES', $definitionManager->getUniqueDefinition('loaders/initial-frames', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				// POPTHEME_WASSUP_ROUTE_MAIN,
				// POPTHEME_WASSUP_ROUTE_DESCRIPTION,
				POPTHEME_WASSUP_ROUTE_SUMMARY,
				// POPTHEME_WASSUP_ROUTE_POSTAUTHORS,
				POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES,
    		]
    	);
    }
);
