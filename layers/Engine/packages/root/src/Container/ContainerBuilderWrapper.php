<?php

declare(strict_types=1);

namespace PoP\Root\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class ContainerBuilderWrapper implements ContainerBuilderWrapperInterface
{
    final public function __construct(
        private readonly ContainerBuilder $containerBuilder
    ) {
    }

    final public function getContainerBuilder(): ContainerBuilder
    {
        return $this->containerBuilder;
    }

    final public function getDefinition(string $id): Definition
    {
        return $this->containerBuilder->getDefinition($id);
    }

    /**
     * @return Definition[] An array of Definition instances
     */
    final public function getDefinitions(): array
    {
        return $this->containerBuilder->getDefinitions();
    }
}
