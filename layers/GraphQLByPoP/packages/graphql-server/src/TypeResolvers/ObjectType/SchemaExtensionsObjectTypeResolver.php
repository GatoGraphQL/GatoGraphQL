<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

class SchemaExtensionsObjectTypeResolver extends AbstractSchemaElementExtensionsObjectTypeResolver
{
    public function getIntrospectionTypeName(): string
    {
        return 'SchemaExtensions';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Extensions (custom metadata) added to the GraphQL schema', 'graphql-server');
    }
}
