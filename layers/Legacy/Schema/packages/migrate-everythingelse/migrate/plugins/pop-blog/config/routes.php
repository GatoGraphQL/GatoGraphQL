<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_BLOG_ROUTE_SEARCHCONTENT')) {
    define('POP_BLOG_ROUTE_SEARCHCONTENT', $definitionManager->getUniqueDefinition('search-content', DefinitionGroups::ROUTES));
}
if (!defined('POP_BLOG_ROUTE_SEARCHUSERS')) {
    define('POP_BLOG_ROUTE_SEARCHUSERS', $definitionManager->getUniqueDefinition('search-users', DefinitionGroups::ROUTES));
}
if (!defined('POP_BLOG_ROUTE_CONTENT')) {
    define('POP_BLOG_ROUTE_CONTENT', $definitionManager->getUniqueDefinition('content', DefinitionGroups::ROUTES));
}
if (!defined('POP_BLOG_ROUTE_COMMENTS')) {
    define('POP_BLOG_ROUTE_COMMENTS', $definitionManager->getUniqueDefinition('comments', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_BLOG_ROUTE_SEARCHCONTENT,
				POP_BLOG_ROUTE_SEARCHUSERS,
				POP_BLOG_ROUTE_CONTENT,
				POP_BLOG_ROUTE_COMMENTS,
    		]
    	);
    }
);
