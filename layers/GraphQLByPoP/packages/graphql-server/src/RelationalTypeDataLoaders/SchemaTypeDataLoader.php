<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\TypeResolvers\Object\SchemaTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\AbstractRelationalTypeDataLoader;
use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
use PoP\ComponentModel\RelationalTypeDataLoaders\UseObjectDictionaryTypeDataLoaderTrait;

class SchemaTypeDataLoader extends AbstractRelationalTypeDataLoader
{
    use UseObjectDictionaryTypeDataLoaderTrait;

    protected function getObjectTypeResolverClass(): string
    {
        return SchemaTypeResolver::class;
    }

    protected function getObjectTypeNewInstance(int | string $id)
    {
        return new Schema(
            $this->getSchemaDefinition($id),
            (string) $id
        );
    }

    protected function &getSchemaDefinition(string $id): array
    {
        $schemaDefinitionReferenceRegistry = SchemaDefinitionReferenceRegistryFacade::getInstance();
        return $schemaDefinitionReferenceRegistry->getFullSchemaDefinition();
    }
}
