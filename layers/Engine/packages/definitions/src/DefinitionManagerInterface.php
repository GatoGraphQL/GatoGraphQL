<?php

declare(strict_types=1);

namespace PoP\Definitions;

interface DefinitionManagerInterface
{
    /**
     * @return array<string, DefinitionResolverInterface>
     */
    public function getDefinitionResolvers(): array;
    public function getDefinitionResolver(string $group): ?DefinitionResolverInterface;
    public function setDefinitionResolver(DefinitionResolverInterface $definition_resolver, string $group): void;
    public function setDefinitionPersistence(DefinitionPersistenceInterface $definition_persistence): void;
    public function getDefinitionPersistence(): ?DefinitionPersistenceInterface;
    public function getDefinition(string $name, string $group): string;
    public function getOriginalName(string $definition, string $group): string;
    public function maybeStoreDefinitionsPersistently(): void;
}
