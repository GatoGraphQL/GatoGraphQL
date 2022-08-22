<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Registries;

use GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinitionReferenceObjectInterface;

interface SchemaDefinitionReferenceRegistryInterface
{
    /**
     * It returns the full schema, expanded with all data required to satisfy GraphQL's introspection fields (starting from "__schema")
     *
     * @return array<string,mixed>
     */
    public function &getFullSchemaDefinitionForGraphQL(): array;
    public function registerSchemaDefinitionReferenceObject(
        SchemaDefinitionReferenceObjectInterface $referenceObject
    ): string;
    public function getSchemaDefinitionReferenceObject(
        string $referenceObjectID
    ): ?SchemaDefinitionReferenceObjectInterface;
}
