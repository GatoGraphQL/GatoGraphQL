<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\Object;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\TypeResolvers\Object\SchemaTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\Object\AbstractObjectTypeDataLoader;
use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
use PoP\ComponentModel\RelationalTypeDataLoaders\Object\UseObjectDictionaryTypeDataLoaderTrait;

class SchemaTypeDataLoader extends AbstractObjectTypeDataLoader
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
