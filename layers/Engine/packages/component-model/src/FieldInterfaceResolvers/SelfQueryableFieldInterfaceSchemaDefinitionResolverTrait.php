<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

trait SelfQueryableFieldInterfaceSchemaDefinitionResolverTrait
{
    public function getFieldDataFilteringModule(string $fieldName): ?array
    {
        return null;
    }
}
