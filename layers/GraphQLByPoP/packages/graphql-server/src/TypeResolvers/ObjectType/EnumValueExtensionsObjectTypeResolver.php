<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

class EnumValueExtensionsObjectTypeResolver extends AbstractSchemaElementExtensionsObjectTypeResolver
{
    public function getIntrospectionTypeName(): string
    {
        return 'EnumValueExtensions';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Extensions (custom metadata) added to the enum value', 'graphql-server');
    }
}
