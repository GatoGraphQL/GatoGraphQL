<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface QueryableFieldSchemaDefinitionResolverInterface extends FieldSchemaDefinitionResolverInterface
{
    public function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName): ?array;
}
