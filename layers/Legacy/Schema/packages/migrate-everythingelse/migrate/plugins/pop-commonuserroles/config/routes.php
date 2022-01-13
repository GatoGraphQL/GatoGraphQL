<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION')) {
    define('POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION', $definitionManager->getUniqueDefinition('add-profile-organization', DefinitionGroups::ROUTES));
}
if (!defined('POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL')) {
    define('POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL', $definitionManager->getUniqueDefinition('add-profile-individual', DefinitionGroups::ROUTES));
}
if (!defined('POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION')) {
    define('POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION', $definitionManager->getUniqueDefinition('edit-profile-organization', DefinitionGroups::ROUTES));
}
if (!defined('POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL')) {
    define('POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL', $definitionManager->getUniqueDefinition('edit-profile-individual', DefinitionGroups::ROUTES));
}
if (!defined('POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS')) {
    define('POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS', $definitionManager->getUniqueDefinition('organizations', DefinitionGroups::ROUTES));
}
if (!defined('POP_COMMONUSERROLES_ROUTE_INDIVIDUALS')) {
    define('POP_COMMONUSERROLES_ROUTE_INDIVIDUALS', $definitionManager->getUniqueDefinition('individuals', DefinitionGroups::ROUTES));
}

HooksAPIFacade::getInstance()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION,
				POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL,
				POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION,
				POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL,
				POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS,
				POP_COMMONUSERROLES_ROUTE_INDIVIDUALS,
    		]
    	);
    }
);
