<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_POSTSCREATION_ROUTE_MYPOSTS')) {
    define('POP_POSTSCREATION_ROUTE_MYPOSTS', $definitionManager->getUniqueDefinition('my-posts', DefinitionGroups::ROUTES));
}
if (!defined('POP_POSTSCREATION_ROUTE_ADDPOST')) {
    define('POP_POSTSCREATION_ROUTE_ADDPOST', $definitionManager->getUniqueDefinition('add-post', DefinitionGroups::ROUTES));
}
if (!defined('POP_POSTSCREATION_ROUTE_EDITPOST')) {
    define('POP_POSTSCREATION_ROUTE_EDITPOST', $definitionManager->getUniqueDefinition('edit-post', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
    			POP_POSTSCREATION_ROUTE_MYPOSTS,
    			POP_POSTSCREATION_ROUTE_ADDPOST,
				POP_POSTSCREATION_ROUTE_EDITPOST,
    		]
    	);
    }
);
