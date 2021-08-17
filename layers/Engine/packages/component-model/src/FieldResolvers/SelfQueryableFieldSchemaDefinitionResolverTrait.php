<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

trait SelfQueryableFieldSchemaDefinitionResolverTrait
{
    public function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return null;
    }
}
