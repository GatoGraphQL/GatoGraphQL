<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

class InputValueExtensionsObjectTypeResolver extends AbstractSchemaElementExtensionsObjectTypeResolver
{
    public function getIntrospectionTypeName(): string
    {
        return 'InputValueExtensions';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Extensions (custom metadata) added to the input value', 'graphql-server');
    }
}
