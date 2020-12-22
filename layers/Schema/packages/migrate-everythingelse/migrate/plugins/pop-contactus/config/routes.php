<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_CONTACTUS_ROUTE_CONTACTUS')) {
	define('POP_CONTACTUS_ROUTE_CONTACTUS', $definitionManager->getUniqueDefinition('contact-us', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_CONTACTUS_ROUTE_CONTACTUS,
    		]
    	);
    }
);