<?php

declare(strict_types=1);

namespace PoPAPI\API\PersistedQueries;

interface PersistedQueryManagerInterface
{
    public function getPersistedQueries(): array;
    public function getPersistedQuery(string $queryName): ?string;
    public function hasPersistedQuery(string $queryName): bool;
    public function addPersistedQuery(string $queryName, string $queryResolution, ?string $description = null): void;
    public function getPersistedQueriesForSchema(): array;
}
