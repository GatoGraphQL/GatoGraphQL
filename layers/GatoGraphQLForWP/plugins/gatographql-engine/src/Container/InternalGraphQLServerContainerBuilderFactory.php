<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container;

use PoP\Root\Container\ContainerBuilderFactory;

class InternalGraphQLServerContainerBuilderFactory extends ContainerBuilderFactory
{
    use InternalGraphQLServerContainerBuilderFactoryTrait;

    /**
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    public function __construct(
        private array $pluginAppGraphQLServerContext,
    ) {
    }

    public function getContainerClassName(): string
    {
        return $this->getInternalGraphQLServerContainerClassName(
            $this->pluginAppGraphQLServerContext,
            parent::getContainerClassName()
        );
    }
}
