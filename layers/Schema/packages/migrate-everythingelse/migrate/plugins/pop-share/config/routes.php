<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_SHARE_ROUTE_SHAREBYEMAIL')) {
	define('POP_SHARE_ROUTE_SHAREBYEMAIL', $definitionManager->getUniqueDefinition('share-by-email', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_SHARE_ROUTE_SHAREBYEMAIL,
    		]
    	);
    }
);