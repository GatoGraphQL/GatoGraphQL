<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\WrappingTypeOrSchemaDefinitionReferenceObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class TypeObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    private ?WrappingTypeOrSchemaDefinitionReferenceObjectTypeDataLoader $wrappingTypeOrSchemaDefinitionReferenceObjectTypeDataLoader = null;

    final protected function getWrappingTypeOrSchemaDefinitionReferenceObjectTypeDataLoader(): WrappingTypeOrSchemaDefinitionReferenceObjectTypeDataLoader
    {
        if ($this->wrappingTypeOrSchemaDefinitionReferenceObjectTypeDataLoader === null) {
            /** @var WrappingTypeOrSchemaDefinitionReferenceObjectTypeDataLoader */
            $wrappingTypeOrSchemaDefinitionReferenceObjectTypeDataLoader = $this->instanceManager->getInstance(WrappingTypeOrSchemaDefinitionReferenceObjectTypeDataLoader::class);
            $this->wrappingTypeOrSchemaDefinitionReferenceObjectTypeDataLoader = $wrappingTypeOrSchemaDefinitionReferenceObjectTypeDataLoader;
        }
        return $this->wrappingTypeOrSchemaDefinitionReferenceObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return '__Type';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of each GraphQL type in the graph', 'graphql-server');
    }

    public function getID(object $object): string|int|null
    {
        /** @var TypeInterface */
        $type = $object;
        return $type->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getWrappingTypeOrSchemaDefinitionReferenceObjectTypeDataLoader();
    }
}
