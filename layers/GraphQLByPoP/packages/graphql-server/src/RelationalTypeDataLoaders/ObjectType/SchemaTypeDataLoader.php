<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractUseObjectDictionaryTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class SchemaTypeDataLoader extends AbstractUseObjectDictionaryTypeDataLoader
{
    private ?SchemaObjectTypeResolver $schemaObjectTypeResolver = null;
    private ?SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry = null;

    final public function setSchemaObjectTypeResolver(SchemaObjectTypeResolver $schemaObjectTypeResolver): void
    {
        $this->schemaObjectTypeResolver = $schemaObjectTypeResolver;
    }
    final protected function getSchemaObjectTypeResolver(): SchemaObjectTypeResolver
    {
        /** @var SchemaObjectTypeResolver */
        return $this->schemaObjectTypeResolver ??= $this->instanceManager->getInstance(SchemaObjectTypeResolver::class);
    }
    final public function setSchemaDefinitionReferenceRegistry(SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry): void
    {
        $this->schemaDefinitionReferenceRegistry = $schemaDefinitionReferenceRegistry;
    }
    final protected function getSchemaDefinitionReferenceRegistry(): SchemaDefinitionReferenceRegistryInterface
    {
        /** @var SchemaDefinitionReferenceRegistryInterface */
        return $this->schemaDefinitionReferenceRegistry ??= $this->instanceManager->getInstance(SchemaDefinitionReferenceRegistryInterface::class);
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getSchemaObjectTypeResolver();
    }

    protected function getObjectTypeNewInstance(int|string $id): mixed
    {
        $fullSchemaDefinition = $this->getSchemaDefinitionReferenceRegistry()->getFullSchemaDefinitionForGraphQL();
        return new Schema(
            $fullSchemaDefinition,
            (string) $id
        );
    }
}
