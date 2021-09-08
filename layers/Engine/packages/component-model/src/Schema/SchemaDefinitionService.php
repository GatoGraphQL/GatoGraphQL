<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class SchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    public function getTypeSchemaKey(TypeResolverInterface $typeResolver): string
    {
        // By default, use the type name
        return $typeResolver->getMaybeNamespacedTypeName();
    }
    /**
     * The `mixed` type is a wildcard type,
     * representing *any* type (string, int, bool, etc)
     */
    public function getDefaultType(): string
    {
        return SchemaDefinition::TYPE_ANY_SCALAR;
    }
}
