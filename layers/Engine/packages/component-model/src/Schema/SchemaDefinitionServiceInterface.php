<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface SchemaDefinitionServiceInterface
{
    public function getTypeSchemaKey(TypeResolverInterface $typeResolver): string;
    /**
     * Field types, and field/directive argument types are mandatory.
     * When not defined, the default type will be used.
     */
    public function getDefaultType(): string;
    /**
     * Field types, and field/directive argument types are mandatory.
     * When not defined, the default type will be used.
     */
    public function getDefaultTypeResolver(): ConcreteTypeResolverInterface;
}
