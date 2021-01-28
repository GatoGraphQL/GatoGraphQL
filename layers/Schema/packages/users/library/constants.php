<?php

declare(strict_types=1);

use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;

if (!defined('POP_USERS_ROUTE_USERS')) {
    $definitionManager = DefinitionManagerFacade::getInstance();
    define('POP_USERS_ROUTE_USERS', $definitionManager->getUniqueDefinition('users', DefinitionGroups::ROUTES));
}
