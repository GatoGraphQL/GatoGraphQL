<?php

declare(strict_types=1);

namespace PoPAPI\GraphQLAPI;

use PoP\Root\Module\AbstractComponent;

/**
 * Initialize component
 */
class Module extends AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPAPI\APIMirrorQuery\Module::class,
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
