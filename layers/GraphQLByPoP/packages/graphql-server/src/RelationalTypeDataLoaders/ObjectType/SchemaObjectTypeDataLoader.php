<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractUseObjectDictionaryObjectTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Exception\ShouldNotHappenException;

class SchemaObjectTypeDataLoader extends AbstractUseObjectDictionaryObjectTypeDataLoader
{
    private ?SchemaObjectTypeResolver $schemaObjectTypeResolver = null;
    private ?SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry = null;

    final public function setSchemaObjectTypeResolver(SchemaObjectTypeResolver $schemaObjectTypeResolver): void
    {
        $this->schemaObjectTypeResolver = $schemaObjectTypeResolver;
    }
    final protected function getSchemaObjectTypeResolver(): SchemaObjectTypeResolver
    {
        if ($this->schemaObjectTypeResolver === null) {
            /** @var SchemaObjectTypeResolver */
            $schemaObjectTypeResolver = $this->instanceManager->getInstance(SchemaObjectTypeResolver::class);
            $this->schemaObjectTypeResolver = $schemaObjectTypeResolver;
        }
        return $this->schemaObjectTypeResolver;
    }
    final public function setSchemaDefinitionReferenceRegistry(SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry): void
    {
        $this->schemaDefinitionReferenceRegistry = $schemaDefinitionReferenceRegistry;
    }
    final protected function getSchemaDefinitionReferenceRegistry(): SchemaDefinitionReferenceRegistryInterface
    {
        if ($this->schemaDefinitionReferenceRegistry === null) {
            /** @var SchemaDefinitionReferenceRegistryInterface */
            $schemaDefinitionReferenceRegistry = $this->instanceManager->getInstance(SchemaDefinitionReferenceRegistryInterface::class);
            $this->schemaDefinitionReferenceRegistry = $schemaDefinitionReferenceRegistry;
        }
        return $this->schemaDefinitionReferenceRegistry;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getSchemaObjectTypeResolver();
    }

    protected function getObjectTypeNewInstance(int|string $id): mixed
    {
        if ($id !== Schema::ID) {
            throw new ShouldNotHappenException(sprintf(
                $this->__('The Schema object data must be unique, so must not create object with ID "%s"', 'gatographql'),
                $id
            ));
        }

        $fullSchemaDefinition = $this->getSchemaDefinitionReferenceRegistry()->getFullSchemaDefinitionForGraphQL();
        return new Schema(
            $fullSchemaDefinition,
            (string) $id
        );
    }
}
