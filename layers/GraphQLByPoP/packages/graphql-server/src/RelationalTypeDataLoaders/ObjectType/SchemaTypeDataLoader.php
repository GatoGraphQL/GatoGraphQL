<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\UseObjectDictionaryTypeDataLoaderTrait;

class SchemaTypeDataLoader extends AbstractObjectTypeDataLoader
{
    use UseObjectDictionaryTypeDataLoaderTrait;

    protected function getObjectTypeResolverClass(): string
    {
        return SchemaObjectTypeResolver::class;
    }

    protected function getObjectTypeNewInstance(int | string $id): mixed
    {
        $schemaDefinition = $this->getSchemaDefinition($id);
        if ($schemaDefinition === null) {
            return null;
        }
        return new Schema(
            $schemaDefinition,
            (string) $id
        );
    }

    protected function &getSchemaDefinition(string $id): ?array
    {
        $schemaDefinitionReferenceRegistry = SchemaDefinitionReferenceRegistryFacade::getInstance();
        return $schemaDefinitionReferenceRegistry->getFullSchemaDefinition();
    }
}
