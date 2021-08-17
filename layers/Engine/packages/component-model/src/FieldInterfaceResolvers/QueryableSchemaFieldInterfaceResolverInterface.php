<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

interface QueryableSchemaFieldInterfaceResolverInterface
{
    public function getFieldDataFilteringModule(string $fieldName): ?array;
}
