<?php
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
$definitionManager = DefinitionManagerFacade::getInstance();

if (!defined('POP_ROUTE_DESCRIPTION')) {
    define('POP_ROUTE_DESCRIPTION', $definitionManager->getUniqueDefinition('description', DefinitionGroups::ROUTES));
}
if (!defined('POP_ROUTE_AUTHORS')) {
    define('POP_ROUTE_AUTHORS', $definitionManager->getUniqueDefinition('authors', DefinitionGroups::ROUTES));
}
