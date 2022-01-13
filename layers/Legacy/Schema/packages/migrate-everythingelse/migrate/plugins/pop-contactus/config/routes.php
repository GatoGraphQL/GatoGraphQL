<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_CONTACTUS_ROUTE_CONTACTUS')) {
	define('POP_CONTACTUS_ROUTE_CONTACTUS', $definitionManager->getUniqueDefinition('contact-us', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_CONTACTUS_ROUTE_CONTACTUS,
    		]
    	);
    }
);
