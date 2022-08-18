<?php

declare(strict_types=1);

namespace PoPAPI\API\PersistedQueries;

use PoPAPI\API\Schema\SchemaDefinition;

abstract class AbstractPersistedQueryManager implements PersistedQueryManagerInterface
{
    /**
     * @var array<string,string>
     */
    protected array $persistedQueries = [];
    /**
     * @var array<string,array<string,string>>
     */
    protected array $persistedQueriesForSchema = [];

    /**
     * @return array<string,array<string,string>>
     */
    public function getPersistedQueriesForSchema(): array
    {
        return $this->persistedQueriesForSchema;
    }

    /**
     * @return array<string,string>
     */
    public function getPersistedQueries(): array
    {
        return $this->persistedQueries;
    }

    public function hasPersistedQuery(string $queryName): bool
    {
        return isset($this->persistedQueries[$queryName]);
    }

    public function getPersistedQuery(string $queryName): ?string
    {
        return $this->persistedQueries[$queryName];
    }

    public function addPersistedQuery(string $queryName, string $queryResolution, ?string $description = null): void
    {
        $this->persistedQueries[$queryName] = $queryResolution;

        $this->persistedQueriesForSchema[$queryName] = [
            SchemaDefinition::NAME => $queryName,
        ];
        if ($description) {
            $this->persistedQueriesForSchema[$queryName][SchemaDefinition::DESCRIPTION] = $description;
        }
        if ($this->addQueryResolutionToSchema()) {
            $this->persistedQueriesForSchema[$queryName][SchemaDefinition::FRAGMENT_RESOLUTION] = $queryResolution;
        }
    }

    abstract protected function addQueryResolutionToSchema(): bool;
}
