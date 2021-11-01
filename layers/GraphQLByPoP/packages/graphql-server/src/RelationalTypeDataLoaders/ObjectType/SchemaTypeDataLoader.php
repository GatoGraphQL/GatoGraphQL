<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use PoP\ComponentModel\Container\ObjectDictionaryInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\UseObjectDictionaryTypeDataLoaderTrait;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaTypeDataLoader extends AbstractObjectTypeDataLoader
{
    use UseObjectDictionaryTypeDataLoaderTrait;

    private ?SchemaObjectTypeResolver $schemaObjectTypeResolver = null;
    private ?SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry = null;
    private ?ObjectDictionaryInterface $objectDictionary = null;

    final public function setSchemaObjectTypeResolver(SchemaObjectTypeResolver $schemaObjectTypeResolver): void
    {
        $this->schemaObjectTypeResolver = $schemaObjectTypeResolver;
    }
    final protected function getSchemaObjectTypeResolver(): SchemaObjectTypeResolver
    {
        return $this->schemaObjectTypeResolver ??= $this->instanceManager->getInstance(SchemaObjectTypeResolver::class);
    }
    final public function setSchemaDefinitionReferenceRegistry(SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry): void
    {
        $this->schemaDefinitionReferenceRegistry = $schemaDefinitionReferenceRegistry;
    }
    final protected function getSchemaDefinitionReferenceRegistry(): SchemaDefinitionReferenceRegistryInterface
    {
        return $this->schemaDefinitionReferenceRegistry ??= $this->instanceManager->getInstance(SchemaDefinitionReferenceRegistryInterface::class);
    }
    final public function setObjectDictionary(ObjectDictionaryInterface $objectDictionary): void
    {
        $this->objectDictionary = $objectDictionary;
    }
    final protected function getObjectDictionary(): ObjectDictionaryInterface
    {
        return $this->objectDictionary ??= $this->instanceManager->getInstance(ObjectDictionaryInterface::class);
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getSchemaObjectTypeResolver();
    }

    protected function getObjectTypeNewInstance(int | string $id): mixed
    {
        $fullSchemaDefinition = $this->getSchemaDefinitionReferenceRegistry()->getFullSchemaDefinitionForGraphQL();
        return new Schema(
            $fullSchemaDefinition,
            (string) $id
        );
    }
}
