<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;

class SchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    public function getInterfaceSchemaKey(FieldInterfaceResolverInterface $interfaceResolver): string
    {
        // By default, use the type name
        return $interfaceResolver->getMaybeNamespacedInterfaceName();
    }
    public function getTypeSchemaKey(TypeResolverInterface $typeResolver): string
    {
        // By default, use the type name
        return $typeResolver->getMaybeNamespacedTypeName();
    }
}
