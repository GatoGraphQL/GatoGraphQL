<?php

declare(strict_types=1);

namespace PoP\Routing;

use PoP\Definitions\DefinitionManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait RoutesTrait
{
    protected DefinitionManagerInterface $definitionManager;

    #[Required]
    final public function autowireRoutesTrait(
        DefinitionManagerInterface $definitionManager,
    ): void {
        $this->definitionManager = $definitionManager;
    }
    
    /**
     * Construct all the routes, each of them having a unique definition
     * (if the same "name" is used for 2 different routes, it throws an exception)
     */
    public static function init(): void
    {
        foreach (self::getRouteNameAndVariableRefs() as $route => &$var) {
            $var = $this->definitionManager->getUniqueDefinition($route, DefinitionGroups::ROUTES);
        }
    }
    /**
     * @return array<string, string>
     */
    protected static function getRouteNameAndVariableRefs(): array
    {
        return [];
    }
}
