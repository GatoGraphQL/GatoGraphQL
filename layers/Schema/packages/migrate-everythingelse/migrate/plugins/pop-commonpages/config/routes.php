<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_COMMONPAGES_ROUTE_ABOUT')) {
    define('POP_COMMONPAGES_ROUTE_ABOUT', $definitionManager->getUniqueDefinition('about', DefinitionGroups::ROUTES));
}
if (!defined('POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE')) {
    define('POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE', $definitionManager->getUniqueDefinition('who-we-are', DefinitionGroups::ROUTES));
}
if (!defined('POP_CLUSTERCOMMONPAGES_ROUTE_ABOUT_OURSPONSORS')) {
    define('POP_CLUSTERCOMMONPAGES_ROUTE_ABOUT_OURSPONSORS', $definitionManager->getUniqueDefinition('our-sponsors', DefinitionGroups::ROUTES));
}
// if (!defined('POP_CLUSTERCOMMONPAGES_PAGE_ABOUT_SPONSORUS')) {
//     define('POP_CLUSTERCOMMONPAGES_PAGE_ABOUT_SPONSORUS', $definitionManager->getUniqueDefinition('sponsor-us', DefinitionGroups::ROUTES));
// }

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_COMMONPAGES_ROUTE_ABOUT,
                POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
                POP_CLUSTERCOMMONPAGES_ROUTE_ABOUT_OURSPONSORS,
                // POP_CLUSTERCOMMONPAGES_PAGE_ABOUT_SPONSORUS,
    		]
    	);
    }
);
