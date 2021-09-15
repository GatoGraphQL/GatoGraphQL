<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Directive;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceTypeDataLoader;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractIntrospectionObjectTypeResolver;

class DirectiveObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    public function getTypeName(): string
    {
        return '__Directive';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('A GraphQL directive in the data graph', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var Directive */
        $directive = $object;
        return $directive->getID();
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return SchemaDefinitionReferenceTypeDataLoader::class;
    }
}
