<?php

declare(strict_types=1);

use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;

if (!defined('POP_POSTTAGS_ROUTE_POSTTAGS')) {
    $definitionManager = DefinitionManagerFacade::getInstance();
    define('POP_POSTTAGS_ROUTE_POSTTAGS', $definitionManager->getUniqueDefinition('post-tags', DefinitionGroups::ROUTES));
}
