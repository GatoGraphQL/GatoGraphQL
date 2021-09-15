<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\EnumValue;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceTypeDataLoader;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractIntrospectionObjectTypeResolver;

class EnumValueObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    public function getTypeName(): string
    {
        return '__EnumValue';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of an Enum value in GraphQL', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var EnumValue */
        $enumValue = $object;
        return $enumValue->getID();
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return SchemaDefinitionReferenceTypeDataLoader::class;
    }
}
