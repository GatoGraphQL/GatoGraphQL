<?php
// use PoP\Hooks\Facades\HooksAPIFacade;
// use PoP\Routing\DefinitionGroups;
// use PoP\Definitions\Facades\DefinitionManagerFacade;
// $definitionManager = DefinitionManagerFacade::getInstance();

// Loader Pages
//--------------------------------------------------------
// @todo Find out if it is required, since using POP_POSTTAGS_ROUTE_POSTTAGS
// if (!defined('POP_TAGS_ROUTE_TAGS')) {
//     define('POP_TAGS_ROUTE_TAGS', $definitionManager->getUniqueDefinition('tags', DefinitionGroups::ROUTES));
// }

// HooksAPIFacade::getInstance()->addFilter(
//     'routes',
//     function($routes) {
//     	return array_merge(
//     		$routes,
//     		[
// 				POP_TAGS_ROUTE_TAGS,
//     		]
//     	);
//     }
// );
