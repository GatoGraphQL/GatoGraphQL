<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

class NamedTypeExtensionsObjectTypeResolver extends AbstractSchemaElementExtensionsObjectTypeResolver
{
    public function getIntrospectionTypeName(): string
    {
        return 'NamedTypeExtensions';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Extensions (custom metadata) added to the GraphQL type (for all \'named\' types: Object, Interface, Union, Scalar, Enum and InputObject)', 'graphql-server');
    }
}
