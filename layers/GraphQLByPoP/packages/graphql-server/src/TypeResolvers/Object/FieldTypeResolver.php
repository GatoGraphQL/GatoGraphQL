<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\Object;

use GraphQLByPoP\GraphQLServer\TypeResolvers\Object\AbstractIntrospectionTypeResolver;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\Object\SchemaDefinitionReferenceTypeDataLoader;

class FieldTypeResolver extends AbstractIntrospectionTypeResolver
{
    public function getTypeName(): string
    {
        return '__Field';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a GraphQL type\'s field', 'graphql-server');
    }

    public function getID(object $resultItem): string | int | null
    {
        $field = $resultItem;
        return $field->getID();
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return SchemaDefinitionReferenceTypeDataLoader::class;
    }
}
