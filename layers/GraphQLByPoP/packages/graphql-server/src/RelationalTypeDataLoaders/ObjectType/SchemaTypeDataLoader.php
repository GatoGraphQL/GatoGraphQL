<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\UseObjectDictionaryTypeDataLoaderTrait;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaTypeDataLoader extends AbstractObjectTypeDataLoader
{
    use UseObjectDictionaryTypeDataLoaderTrait;

    protected SchemaObjectTypeResolver $schemaObjectTypeResolver;
    protected SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry;

    #[Required]
    final public function autowireSchemaTypeDataLoader(
        SchemaObjectTypeResolver $schemaObjectTypeResolver,
        SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry,
    ): void {
        $this->schemaObjectTypeResolver = $schemaObjectTypeResolver;
        $this->schemaDefinitionReferenceRegistry = $schemaDefinitionReferenceRegistry;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->schemaObjectTypeResolver;
    }

    protected function getObjectTypeNewInstance(int | string $id): mixed
    {
        $fullSchemaDefinition = $this->schemaDefinitionReferenceRegistry->getFullSchemaDefinitionForGraphQL();
        return new Schema(
            $fullSchemaDefinition,
            (string) $id
        );
    }
}
