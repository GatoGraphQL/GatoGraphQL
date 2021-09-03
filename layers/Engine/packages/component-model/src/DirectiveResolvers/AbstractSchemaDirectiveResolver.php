<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;

abstract class AbstractSchemaDirectiveResolver extends AbstractDirectiveResolver implements SchemaDirectiveResolverInterface
{
    public function getSchemaDefinitionResolver(ObjectTypeResolverInterface $typeResolver): ?SchemaDirectiveResolverInterface
    {
        return $this;
    }
    public function getSchemaDirectiveDescription(ObjectTypeResolverInterface $typeResolver): ?string
    {
        return null;
    }
    public function getSchemaDirectiveWarningDescription(ObjectTypeResolverInterface $typeResolver): ?string
    {
        return null;
    }
    public function getSchemaDirectiveDeprecationDescription(ObjectTypeResolverInterface $typeResolver): ?string
    {
        return null;
    }
    public function getSchemaDirectiveExpressions(ObjectTypeResolverInterface $typeResolver): array
    {
        return [];
    }
    public function getSchemaDirectiveArgs(ObjectTypeResolverInterface $typeResolver): array
    {
        return [];
    }
    public function enableOrderedSchemaDirectiveArgs(ObjectTypeResolverInterface $typeResolver): bool
    {
        return true;
    }
    public function isGlobal(ObjectTypeResolverInterface $typeResolver): bool
    {
        return false;
    }
}
