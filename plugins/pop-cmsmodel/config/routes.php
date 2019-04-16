<?php

// Loader Pages
//--------------------------------------------------------
if (!defined('POP_CMSMODEL_ROUTE_LOADERS_POSTS_FIELDS')) {
	define('POP_CMSMODEL_ROUTE_LOADERS_POSTS_FIELDS', \PoP\Engine\DefinitionUtils::getDefinition('loaders/posts/fields', POP_DEFINITIONGROUP_ROUTES));
}
if (!defined('POP_CMSMODEL_ROUTE_LOADERS_USERS_FIELDS')) {
	define('POP_CMSMODEL_ROUTE_LOADERS_USERS_FIELDS', \PoP\Engine\DefinitionUtils::getDefinition('loaders/users/fields', POP_DEFINITIONGROUP_ROUTES));
}
if (!defined('POP_CMSMODEL_ROUTE_LOADERS_TAGS_FIELDS')) {
	define('POP_CMSMODEL_ROUTE_LOADERS_TAGS_FIELDS', \PoP\Engine\DefinitionUtils::getDefinition('loaders/tags/fields', POP_DEFINITIONGROUP_ROUTES));
}
if (!defined('POP_CMSMODEL_ROUTE_LOADERS_COMMENTS_FIELDS')) {
	define('POP_CMSMODEL_ROUTE_LOADERS_COMMENTS_FIELDS', \PoP\Engine\DefinitionUtils::getDefinition('loaders/comments/fields', POP_DEFINITIONGROUP_ROUTES));
}
if (!defined('POP_CMSMODEL_ROUTE_LOADERS_POSTS_LAYOUTS')) {
	define('POP_CMSMODEL_ROUTE_LOADERS_POSTS_LAYOUTS', \PoP\Engine\DefinitionUtils::getDefinition('loaders/posts/layouts', POP_DEFINITIONGROUP_ROUTES));
}
if (!defined('POP_CMSMODEL_ROUTE_LOADERS_USERS_LAYOUTS')) {
	define('POP_CMSMODEL_ROUTE_LOADERS_USERS_LAYOUTS', \PoP\Engine\DefinitionUtils::getDefinition('loaders/users/layouts', POP_DEFINITIONGROUP_ROUTES));
}
if (!defined('POP_CMSMODEL_ROUTE_LOADERS_TAGS_LAYOUTS')) {
	define('POP_CMSMODEL_ROUTE_LOADERS_TAGS_LAYOUTS', \PoP\Engine\DefinitionUtils::getDefinition('loaders/tags/layouts', POP_DEFINITIONGROUP_ROUTES));
}
if (!defined('POP_CMSMODEL_ROUTE_LOADERS_COMMENTS_LAYOUTS')) {
	define('POP_CMSMODEL_ROUTE_LOADERS_COMMENTS_LAYOUTS', \PoP\Engine\DefinitionUtils::getDefinition('loaders/comments/layouts', POP_DEFINITIONGROUP_ROUTES));
}

\PoP\CMS\HooksAPI_Factory::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_CMSMODEL_ROUTE_LOADERS_POSTS_FIELDS,
				POP_CMSMODEL_ROUTE_LOADERS_USERS_FIELDS,
				POP_CMSMODEL_ROUTE_LOADERS_TAGS_FIELDS,
				POP_CMSMODEL_ROUTE_LOADERS_COMMENTS_FIELDS,
				POP_CMSMODEL_ROUTE_LOADERS_POSTS_LAYOUTS,
				POP_CMSMODEL_ROUTE_LOADERS_USERS_LAYOUTS,
				POP_CMSMODEL_ROUTE_LOADERS_TAGS_LAYOUTS,
				POP_CMSMODEL_ROUTE_LOADERS_COMMENTS_LAYOUTS,
    		]
    	);
    }
);