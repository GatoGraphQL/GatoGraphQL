<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\Component\Component;
interface QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface extends InterfaceTypeFieldSchemaDefinitionResolverInterface
{
    public function getFieldFilterInputContainerComponent(string $fieldName): ?Component;
}
