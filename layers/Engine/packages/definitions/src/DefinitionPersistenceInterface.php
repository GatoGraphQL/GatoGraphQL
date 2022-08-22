<?php

declare(strict_types=1);

namespace PoP\Definitions;

interface DefinitionPersistenceInterface
{
    /**
     * @return array<string,DefinitionResolverInterface>
     */
    public function getDefinitionResolvers(): array;
    public function storeDefinitionsPersistently(): void;
    public function getSavedDefinition(string $name, string $group): ?string;
    public function getOriginalName(string $definition, string $group): ?string;
    public function saveDefinition(string $definition, string $name, string $group): void;
    public function setDefinitionResolver(DefinitionResolverInterface $definition_resolver, string $group): void;
}
