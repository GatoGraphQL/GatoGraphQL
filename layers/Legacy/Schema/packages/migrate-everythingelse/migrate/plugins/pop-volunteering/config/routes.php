<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER')) {
	define('POP_VOLUNTEERING_ROUTE_VOLUNTEER', $definitionManager->getUniqueDefinition('volunteer', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\RootWP\Routing\HookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_VOLUNTEERING_ROUTE_VOLUNTEER,
    		]
    	);
    }
);
