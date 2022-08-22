<?php

declare(strict_types=1);

namespace PoP\Definitions;

interface DefinitionResolverInterface
{
    public function getDefinition(string $name, string $group): string;
    /**
     * @return array<string,mixed>
     */
    public function getDataToPersist(): array;
    /**
     * Allow Persistent Definitions to set a different value
     *
     * @param array<string,mixed> $persisted_data
     */
    public function setPersistedData(array $persisted_data): void;
}
