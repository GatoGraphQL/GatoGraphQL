<?php
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

// public const POP_ROUTE_DESCRIPTION = 'description';
// public const POP_ROUTE_AUTHORS = 'authors';
if (!defined('POP_ROUTE_DESCRIPTION')) {
    define('POP_ROUTE_DESCRIPTION', $definitionManager->getUniqueDefinition('description', DefinitionGroups::ROUTES));
}
if (!defined('POP_ROUTE_AUTHORS')) {
    define('POP_ROUTE_AUTHORS', $definitionManager->getUniqueDefinition('authors', DefinitionGroups::ROUTES));
}