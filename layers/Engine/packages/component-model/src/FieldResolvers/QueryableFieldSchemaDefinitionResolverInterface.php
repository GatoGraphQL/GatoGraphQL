<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface QueryableFieldSchemaDefinitionResolverInterface extends FieldSchemaDefinitionResolverInterface
{
    public function getFieldDataFilteringModule(RelationalTypeResolverInterface $typeResolver, string $fieldName): ?array;
}
