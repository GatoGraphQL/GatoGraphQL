<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface SchemaDefinitionServiceInterface
{
    public function getInterfaceSchemaKey(FieldInterfaceResolverInterface $interfaceResolver): string;
    public function getTypeSchemaKey(RelationalTypeResolverInterface $relationalTypeResolver): string;
    /**
     * Field types, and field/directive argument types are mandatory.
     * When not defined, the default type will be used.
     */
    public function getDefaultType(): string;
}
