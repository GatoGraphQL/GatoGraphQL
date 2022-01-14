<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_USERSTANCE_ROUTE_MYSTANCES')) {
	define('POP_USERSTANCE_ROUTE_MYSTANCES', $definitionManager->getUniqueDefinition('my-stances', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_ADDSTANCE')) {
	define('POP_USERSTANCE_ROUTE_ADDSTANCE', $definitionManager->getUniqueDefinition('add-stance', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_EDITSTANCE')) {
	define('POP_USERSTANCE_ROUTE_EDITSTANCE', $definitionManager->getUniqueDefinition('edit-stance', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_ADDOREDITSTANCE')) {
	define('POP_USERSTANCE_ROUTE_ADDOREDITSTANCE', $definitionManager->getUniqueDefinition('add-or-edit-stance', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_STANCES')) {
	define('POP_USERSTANCE_ROUTE_STANCES', $definitionManager->getUniqueDefinition('stances', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_STANCES_PRO')) {
	define('POP_USERSTANCE_ROUTE_STANCES_PRO', $definitionManager->getUniqueDefinition('stances/pro', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_STANCES_AGAINST')) {
	define('POP_USERSTANCE_ROUTE_STANCES_AGAINST', $definitionManager->getUniqueDefinition('stances/against', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_STANCES_NEUTRAL')) {
	define('POP_USERSTANCE_ROUTE_STANCES_NEUTRAL', $definitionManager->getUniqueDefinition('stances/neutral', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL')) {
	define('POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL', $definitionManager->getUniqueDefinition('stances/pro/general', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL')) {
	define('POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL', $definitionManager->getUniqueDefinition('stances/against/eneral', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL')) {
	define('POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL', $definitionManager->getUniqueDefinition('stances/neutral/general', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE')) {
	define('POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE', $definitionManager->getUniqueDefinition('stances/pro/article', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE')) {
	define('POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE', $definitionManager->getUniqueDefinition('stances/against/article', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE')) {
	define('POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE', $definitionManager->getUniqueDefinition('stances/neutral/article', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS')) {
	define('POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS', $definitionManager->getUniqueDefinition('stances/by-organizations', DefinitionGroups::ROUTES));
}
if (!defined('POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS')) {
	define('POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS', $definitionManager->getUniqueDefinition('stances/by-individuals', DefinitionGroups::ROUTES));
}

\PoP\Root\App::addFilter(
    \PoP\RoutingWP\HookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_USERSTANCE_ROUTE_MYSTANCES,
				POP_USERSTANCE_ROUTE_ADDSTANCE,
				POP_USERSTANCE_ROUTE_EDITSTANCE,
				POP_USERSTANCE_ROUTE_ADDOREDITSTANCE,
				POP_USERSTANCE_ROUTE_STANCES,
				POP_USERSTANCE_ROUTE_STANCES_PRO,
				POP_USERSTANCE_ROUTE_STANCES_AGAINST,
				POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
				POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
				POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
				POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
				POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
				POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
				POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
				POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
				POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
    		]
    	);
    }
);
