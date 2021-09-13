<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaTypeDataLoader;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractIntrospectionObjectTypeResolver;

class SchemaObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    public function getTypeName(): string
    {
        return '__Schema';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Schema type, to implement the introspection fields', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        $schema = $object;
        return $schema->getID();
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return SchemaTypeDataLoader::class;
    }
}
