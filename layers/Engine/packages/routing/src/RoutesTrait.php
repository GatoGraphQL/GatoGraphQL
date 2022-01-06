<?php

declare(strict_types=1);

namespace PoP\Routing;

use PoP\Definitions\Facades\DefinitionManagerFacade;

trait RoutesTrait
{
    /**
     * Construct all the routes, each of them having a unique definition
     * (if the same "name" is used for 2 different routes, it throws an exception)
     */
    public static function init(): void
    {
        $definitionManager = DefinitionManagerFacade::getInstance();
        foreach (self::getRouteNameAndVariableRefs() as $route => &$var) {
            $var = $definitionManager->getUniqueDefinition($route, DefinitionGroups::ROUTES);
        }
    }
    /**
     * @return array<string, string>
     */
    abstract protected static function getRouteNameAndVariableRefs(): array;
}
