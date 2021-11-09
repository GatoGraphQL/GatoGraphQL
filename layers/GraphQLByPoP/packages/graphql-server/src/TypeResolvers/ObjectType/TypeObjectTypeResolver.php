<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class TypeObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    private ?WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader $wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader = null;

    final public function setWrappingTypeOrSchemaDefinitionReferenceTypeDataLoader(WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader $wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader): void
    {
        $this->wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader = $wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader;
    }
    final protected function getWrappingTypeOrSchemaDefinitionReferenceTypeDataLoader(): WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader
    {
        return $this->wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader ??= $this->instanceManager->getInstance(WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return '__Type';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Representation of each GraphQL type in the graph', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var TypeInterface */
        $type = $object;
        return $type->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getWrappingTypeOrSchemaDefinitionReferenceTypeDataLoader();
    }
}
