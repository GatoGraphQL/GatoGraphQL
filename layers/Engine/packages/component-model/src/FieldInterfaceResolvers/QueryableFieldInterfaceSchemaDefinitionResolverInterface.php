<?php

declare(strict_types=1);

namespace PoP\ComponentModel\InterfaceTypeFieldResolvers;

interface QueryableFieldInterfaceSchemaDefinitionResolverInterface extends FieldInterfaceSchemaDefinitionResolverInterface
{
    public function getFieldDataFilteringModule(string $fieldName): ?array;
}
