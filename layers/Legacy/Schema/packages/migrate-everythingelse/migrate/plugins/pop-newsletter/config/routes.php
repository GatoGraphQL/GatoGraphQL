<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_NEWSLETTER_ROUTE_NEWSLETTER')) {
    define('POP_NEWSLETTER_ROUTE_NEWSLETTER', $definitionManager->getUniqueDefinition('newsletter', DefinitionGroups::ROUTES));
}
if (!defined('POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION')) {
    define('POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION', $definitionManager->getUniqueDefinition('newsletter-unsubscription', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
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
