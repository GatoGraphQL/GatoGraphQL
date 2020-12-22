<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS')) {
	define('POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS', $definitionManager->getUniqueDefinition('postlinks', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
    		]
    	);
    }
);