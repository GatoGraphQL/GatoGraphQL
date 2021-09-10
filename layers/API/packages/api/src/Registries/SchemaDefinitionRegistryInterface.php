<?php

declare(strict_types=1);

namespace PoP\API\Registries;

interface SchemaDefinitionRegistryInterface
{
    /**
     * Produce the schema definition. It can store the value in the cache.
     * Use cache with care: if the schema is dynamic, it should not be cached.
     * Public schema: can cache, Private schema: cannot cache.
     *
     * Return null if retrieving the schema data via field "fullSchema" failed
     */
    public function &getSchemaDefinition(?array $fieldArgs = [], ?array $options = []): ?array;
}
