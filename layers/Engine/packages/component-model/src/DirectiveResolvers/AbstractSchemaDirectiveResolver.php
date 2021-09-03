<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

abstract class AbstractSchemaDirectiveResolver extends AbstractDirectiveResolver implements SchemaDirectiveResolverInterface
{
    public function getSchemaDefinitionResolver(RelationalTypeResolverInterface $relationalTypeResolver): ?SchemaDirectiveResolverInterface
    {
        return $this;
    }
    public function getSchemaDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return null;
    }
    public function getSchemaDirectiveWarningDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return null;
    }
    public function getSchemaDirectiveDeprecationDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return null;
    }
    public function getSchemaDirectiveExpressions(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [];
    }
    public function getSchemaDirectiveArgs(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [];
    }
    public function enableOrderedSchemaDirectiveArgs(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return true;
    }
    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return false;
    }
}
