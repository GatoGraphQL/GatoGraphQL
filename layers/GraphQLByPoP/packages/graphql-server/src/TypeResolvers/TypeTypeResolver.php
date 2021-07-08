<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use GraphQLByPoP\GraphQLServer\TypeResolvers\AbstractIntrospectionTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeDataLoaders\SchemaDefinitionReferenceTypeDataLoader;

class TypeTypeResolver extends AbstractIntrospectionTypeResolver
{
    public function getTypeName(): string
    {
        return '__Type';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of each GraphQL type in the graph', 'graphql-server');
    }

    public function getID(object $resultItem): string | int | null
    {
        $type = $resultItem;
        return $type->getID();
    }

    public function getTypeDataLoaderClass(): string
    {
        return SchemaDefinitionReferenceTypeDataLoader::class;
    }
}
