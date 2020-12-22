<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS')) {
    define('POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS', $definitionManager->getUniqueDefinition('my-postlinks', DefinitionGroups::ROUTES));
}
if (!defined('POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK')) {
    define('POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK', $definitionManager->getUniqueDefinition('add-postlink', DefinitionGroups::ROUTES));
}
if (!defined('POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK')) {
    define('POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK', $definitionManager->getUniqueDefinition('edit-postlink', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS,
				POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
				POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK,
    		]
    	);
    }
);