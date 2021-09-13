<?php

declare(strict_types=1);

namespace PoP\ComponentModel\InterfaceTypeFieldResolvers;

interface QueryableInterfaceTypeObjectTypeFieldSchemaDefinitionResolverInterface extends InterfaceTypeObjectTypeFieldSchemaDefinitionResolverInterface
{
    public function getFieldDataFilteringModule(string $fieldName): ?array;
}
