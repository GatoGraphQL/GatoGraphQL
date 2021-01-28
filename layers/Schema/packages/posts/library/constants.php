<?php

declare(strict_types=1);

use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;

if (!defined('POP_POSTS_ROUTE_POSTS')) {
    $definitionManager = DefinitionManagerFacade::getInstance();
    define('POP_POSTS_ROUTE_POSTS', $definitionManager->getUniqueDefinition('posts', DefinitionGroups::ROUTES));
}
