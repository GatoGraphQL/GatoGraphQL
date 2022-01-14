<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

if (!defined('POP_ROUTE_DESCRIPTION')) {
    define('POP_ROUTE_DESCRIPTION', $definitionManager->getUniqueDefinition('description', DefinitionGroups::ROUTES));
}
if (!defined('POP_ROUTE_AUTHORS')) {
    define('POP_ROUTE_AUTHORS', $definitionManager->getUniqueDefinition('authors', DefinitionGroups::ROUTES));
}
