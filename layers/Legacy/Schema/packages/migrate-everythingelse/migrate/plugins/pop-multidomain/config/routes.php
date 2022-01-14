<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_MULTIDOMAIN_ROUTE_EXTERNAL')) {
    define('POP_MULTIDOMAIN_ROUTE_EXTERNAL', $definitionManager->getUniqueDefinition('external', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\RootWP\Routing\HookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_MULTIDOMAIN_ROUTE_EXTERNAL,
    		]
    	);
    }
);
