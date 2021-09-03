<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;

class SchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    public function getInterfaceSchemaKey(FieldInterfaceResolverInterface $interfaceResolver): string
    {
        // By default, use the type name
        return $interfaceResolver->getMaybeNamespacedInterfaceName();
    }
    public function getTypeSchemaKey(RelationalTypeResolverInterface $relationalTypeResolver): string
    {
        // By default, use the type name
        return $relationalTypeResolver->getMaybeNamespacedTypeName();
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
