<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_USERCOMMUNITIES_ROUTE_COMMUNITIES')) {
    define('POP_USERCOMMUNITIES_ROUTE_COMMUNITIES', $definitionManager->getUniqueDefinition('communities', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERCOMMUNITIES_ROUTE_MEMBERS')) {
    define('POP_USERCOMMUNITIES_ROUTE_MEMBERS', $definitionManager->getUniqueDefinition('members', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERCOMMUNITIES_ROUTE_COMMUNITYPLUSMEMBERS')) {
    define('POP_USERCOMMUNITIES_ROUTE_COMMUNITYPLUSMEMBERS', $definitionManager->getUniqueDefinition('community-plus-members', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES')) {
    define('POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES', $definitionManager->getUniqueDefinition('my-communities', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERCOMMUNITIES_ROUTE_MYMEMBERS')) {
    define('POP_USERCOMMUNITIES_ROUTE_MYMEMBERS', $definitionManager->getUniqueDefinition('my-members', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP')) {
    define('POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP', $definitionManager->getUniqueDefinition('edit-membership', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS')) {
    define('POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS', $definitionManager->getUniqueDefinition('invite-new-members', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\RootWP\Routing\HookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
				POP_USERCOMMUNITIES_ROUTE_MEMBERS,
				POP_USERCOMMUNITIES_ROUTE_COMMUNITYPLUSMEMBERS,
				POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES,
				POP_USERCOMMUNITIES_ROUTE_MYMEMBERS,
				POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP,
				POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS,
    		]
    	);
    }
);
