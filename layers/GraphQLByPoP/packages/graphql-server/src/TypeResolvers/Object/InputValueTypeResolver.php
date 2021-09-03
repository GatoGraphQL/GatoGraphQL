<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\Object;

use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\Object\SchemaDefinitionReferenceTypeDataLoader;
use GraphQLByPoP\GraphQLServer\TypeResolvers\Object\AbstractIntrospectionTypeResolver;

class InputValueTypeResolver extends AbstractIntrospectionTypeResolver
{
    public function getTypeName(): string
    {
        return '__InputValue';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of an input object in GraphQL', 'graphql-server');
    }

    public function getID(object $resultItem): string | int | null
    {
        $inputValue = $resultItem;
        return $inputValue->getID();
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return SchemaDefinitionReferenceTypeDataLoader::class;
    }
}
