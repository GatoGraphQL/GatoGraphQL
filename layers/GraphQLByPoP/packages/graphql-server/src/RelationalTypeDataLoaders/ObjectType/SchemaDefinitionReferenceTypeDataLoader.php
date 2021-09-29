<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaDefinitionReferenceTypeDataLoader extends AbstractObjectTypeDataLoader
{
    protected SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry;

    #[Required]
    public function autowireSchemaDefinitionReferenceTypeDataLoader(
        SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry,
    ): void {
        $this->schemaDefinitionReferenceRegistry = $schemaDefinitionReferenceRegistry;
    }

    /**
     * @return AbstractSchemaDefinitionReferenceObject[]
     */
    public function getObjects(array $ids): array
    {
        // Filter out potential `null` results
        return array_filter(array_map(
            fn ($schemaDefinitionID) => $this->schemaDefinitionReferenceRegistry->getSchemaDefinitionReference($schemaDefinitionID),
            $ids
        ));
    }
}
