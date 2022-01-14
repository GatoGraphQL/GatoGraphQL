<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_NEWSLETTER_ROUTE_NEWSLETTER')) {
    define('POP_NEWSLETTER_ROUTE_NEWSLETTER', $definitionManager->getUniqueDefinition('newsletter', DefinitionGroups::ROUTES));
}
if (!defined('POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION')) {
    define('POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION', $definitionManager->getUniqueDefinition('newsletter-unsubscription', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\RoutingWP\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_NEWSLETTER_ROUTE_NEWSLETTER,
				POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION,
    		]
    	);
    }
);
