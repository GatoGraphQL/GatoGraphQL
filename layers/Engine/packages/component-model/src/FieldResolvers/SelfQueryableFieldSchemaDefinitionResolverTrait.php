<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;

trait SelfQueryableFieldSchemaDefinitionResolverTrait
{
    public function getFieldDataFilteringModule(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return null;
    }
}
