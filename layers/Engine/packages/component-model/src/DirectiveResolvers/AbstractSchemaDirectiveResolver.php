<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

abstract class AbstractSchemaDirectiveResolver extends AbstractDirectiveResolver implements SchemaDirectiveResolverInterface
{
    public function getSchemaDefinitionResolver(RelationalTypeResolverInterface $typeResolver): ?SchemaDirectiveResolverInterface
    {
        return $this;
    }
    public function getSchemaDirectiveDescription(RelationalTypeResolverInterface $typeResolver): ?string
    {
        return null;
    }
    public function getSchemaDirectiveWarningDescription(RelationalTypeResolverInterface $typeResolver): ?string
    {
        return null;
    }
    public function getSchemaDirectiveDeprecationDescription(RelationalTypeResolverInterface $typeResolver): ?string
    {
        return null;
    }
    public function getSchemaDirectiveExpressions(RelationalTypeResolverInterface $typeResolver): array
    {
        return [];
    }
    public function getSchemaDirectiveArgs(RelationalTypeResolverInterface $typeResolver): array
    {
        return [];
    }
    public function enableOrderedSchemaDirectiveArgs(RelationalTypeResolverInterface $typeResolver): bool
    {
        return true;
    }
    public function isGlobal(RelationalTypeResolverInterface $typeResolver): bool
    {
        return false;
    }
}
