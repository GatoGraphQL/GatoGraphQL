<?php

declare(strict_types=1);

namespace PoPAPI\API\PersistedQueries;

interface PersistedQueryManagerInterface
{
    /**
     * @return string[]
     */
    public function getPersistedQueries(): array;
    public function getPersistedQuery(string $queryName): ?string;
    public function hasPersistedQuery(string $queryName): bool;
    public function addPersistedQuery(string $queryName, string $queryResolution, ?string $description = null): void;
    /**
     * @return string[]
     */
    public function getPersistedQueriesForSchema(): array;
}
