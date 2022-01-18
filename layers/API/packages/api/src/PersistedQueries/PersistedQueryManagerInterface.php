<?php

declare(strict_types=1);

namespace PoPAPI\API\PersistedQueries;

interface PersistedQueryManagerInterface
{
    public function getPersistedQueries(): array;
    public function getPersistedQuery(string $queryName): ?string;
    public function hasPersistedQuery(string $queryName): bool;
    /**
     * If the query starts with "!" then it is the query name to a persisted query
     */
    public function isPersistedQuery(string $query): bool;
    /**
     * Remove "!" to get the persisted query name
     */
    public function getPersistedQueryName(string $query): string;
    public function addPersistedQuery(string $queryName, string $queryResolution, ?string $description = null): void;
    public function getPersistedQueriesForSchema(): array;
}
