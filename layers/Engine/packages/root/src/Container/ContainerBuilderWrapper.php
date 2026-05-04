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
     * Returns the container's service definitions, filtering out
     * Symfony's `.abstract.instanceof.*` placeholders. Those are
     * created automatically by `ResolveInstanceofConditionalsPass`
     * for every service touched by an autoconfiguration rule, share
     * the same `getClass()` as the real service, and would otherwise
     * cause class-scanning compiler passes to create references to
     * abstract definitions (which Symfony rejects).
     *
     * @return array<string,Definition> An array of Definition instances
     */
    final public function getDefinitions(): array
    {
        return array_filter(
            $this->containerBuilder->getDefinitions(),
            static fn (Definition $definition): bool => !$definition->isAbstract()
        );
    }

    /**
     * Same abstract-definition filter as `getDefinitions()`.
     *
     * @return array<string,array<array<string,mixed>>> Map of service id => list of tag attributes
     */
    final public function findTaggedServiceIds(string $tag): array
    {
        $result = [];
        foreach ($this->containerBuilder->findTaggedServiceIds($tag) as $id => $tags) {
            if ($this->containerBuilder->getDefinition($id)->isAbstract()) {
                continue;
            }
            $result[$id] = $tags;
        }
        return $result;
    }
}
