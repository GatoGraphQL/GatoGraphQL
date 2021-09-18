<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\Field;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceTypeDataLoader;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractIntrospectionObjectTypeResolver;

class FieldObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    public function getTypeName(): string
    {
        return '__Field';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a GraphQL type\'s field', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var Field */
        $field = $object;
        return $field->getID();
    }

    public function getRelationalTypeDataLoaderClass(): RelationalTypeDataLoaderInterface
    {
        return SchemaDefinitionReferenceTypeDataLoader::class;
    }
}
