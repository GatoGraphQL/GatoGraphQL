<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

class FieldExtensionsObjectTypeResolver extends AbstractSchemaElementExtensionsObjectTypeResolver
{
    public function getIntrospectionTypeName(): string
    {
        return 'FieldExtensions';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Extensions (custom metadata) added to the field', 'graphql-server');
    }
}
