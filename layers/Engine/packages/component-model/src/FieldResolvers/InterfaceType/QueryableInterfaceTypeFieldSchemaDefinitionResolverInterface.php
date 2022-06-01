<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

interface QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface extends InterfaceTypeFieldSchemaDefinitionResolverInterface
{
    public function getFieldFilterInputContainerComponent(string $fieldName): ?\PoP\ComponentModel\Component\Component;
}
