<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait SelfQueryableFieldSchemaDefinitionResolverTrait
{
    public function getFieldDataFilteringModule(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?array
    {
        return null;
    }
}
