<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceTypeDataLoader;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractIntrospectionObjectTypeResolver;

class TypeObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    public function getTypeName(): string
    {
        return '__Type';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of each GraphQL type in the graph', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var AbstractType */
        $type = $object;
        return $type->getID();
    }

    public function getRelationalTypeDataLoaderClass(): RelationalTypeDataLoaderInterface
    {
        return SchemaDefinitionReferenceTypeDataLoader::class;
    }
}
