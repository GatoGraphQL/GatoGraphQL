<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
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

HooksAPIFacade::getInstance()->addFilter(
    'routes',
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