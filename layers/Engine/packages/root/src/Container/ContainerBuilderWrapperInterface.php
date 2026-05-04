<?php

declare(strict_types=1);

namespace PoP\Root\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

interface ContainerBuilderWrapperInterface
{
    public function getContainerBuilder(): ContainerBuilder;
    public function getDefinition(string $id): Definition;
    /**
     * @return array<string,Definition> An array of Definition instances
     */
    public function getDefinitions(): array;
    /**
     * @return array<string,array<array<string,mixed>>> Map of service id => list of tag attributes
     */
    public function findTaggedServiceIds(string $tag): array;
}
