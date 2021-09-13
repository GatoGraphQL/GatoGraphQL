<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

interface QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface extends InterfaceTypeFieldSchemaDefinitionResolverInterface
{
    public function getFieldDataFilteringModule(string $fieldName): ?array;
}
