<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS')) {
    define('POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS', $definitionManager->getUniqueDefinition('highlights', DefinitionGroups::ROUTES));
}
if (!defined('POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS')) {
    define('POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS', $definitionManager->getUniqueDefinition('my-highlights', DefinitionGroups::ROUTES));
}
if (!defined('POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT')) {
    define('POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT', $definitionManager->getUniqueDefinition('add-highlight', DefinitionGroups::ROUTES));
}
if (!defined('POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT')) {
    define('POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT', $definitionManager->getUniqueDefinition('edit-highlight', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
				POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS,
				POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT,
				POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT,
    		]
    	);
    }
);
