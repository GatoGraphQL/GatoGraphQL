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
        if ($id !== Schema::ID) {
            throw new ShouldNotHappenException(sprintf(
                $this->__('The Schema object data must be unique, so must not create object with ID "%s"', 'gato-graphql'),
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
