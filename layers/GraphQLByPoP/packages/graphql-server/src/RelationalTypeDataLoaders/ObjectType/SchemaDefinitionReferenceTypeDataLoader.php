<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\WrappingTypeOrSchemaDefinitionReferenceObjectInterface;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaDefinitionReferenceTypeDataLoader extends AbstractObjectTypeDataLoader
{
    protected SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry;

    #[Required]
    final public function autowireSchemaDefinitionReferenceTypeDataLoader(
        SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry,
    ): void {
        $this->schemaDefinitionReferenceRegistry = $schemaDefinitionReferenceRegistry;
    }

    public function getObjects(array $ids): array
    {
        return array_map(
            fn (string $typeID) => $this->schemaDefinitionReferenceRegistry->getSchemaDefinitionReferenceObject($typeID),
            $ids
        );
    }
}
