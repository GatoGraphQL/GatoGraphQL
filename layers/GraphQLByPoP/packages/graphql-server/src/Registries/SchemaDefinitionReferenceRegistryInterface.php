<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Registries;

use GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinitionReferenceObjectInterface;

interface SchemaDefinitionReferenceRegistryInterface
{
    /**
     * It returns the full schema, expanded with all data required to satisfy GraphQL's introspection fields (starting from "__schema")
     */
    public function &getFullSchemaDefinitionForGraphQL(): array;
    public function registerSchemaDefinitionReferenceObject(
        SchemaDefinitionReferenceObjectInterface $referenceObject
    ): void;
    public function getSchemaDefinitionReferenceObject(
        string $id
    ): ?SchemaDefinitionReferenceObjectInterface;
    /**
     * @param string[] $ids
     * @return SchemaDefinitionReferenceObjectInterface[]
     */
    public function getSchemaDefinitionReferenceObjects(
        array $ids
    ): array;
}
