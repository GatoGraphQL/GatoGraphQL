<?php

declare(strict_types=1);

namespace PoP\GraphQLAPI;

use PoP\Root\App;
use PoP\API\Component as APIComponent;
use PoP\BasicService\Component\AbstractComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoP\APIMirrorQuery\Component::class,
        ];
    }

    protected function resolveEnabled(): bool
    {
        return !Environment::disableGraphQLAPI();
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaComponentClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
    }
}
