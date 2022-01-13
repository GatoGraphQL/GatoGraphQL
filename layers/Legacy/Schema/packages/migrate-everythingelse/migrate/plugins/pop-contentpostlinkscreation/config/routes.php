<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Routing\DefinitionGroups;
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

\PoP\Root\App::addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
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
