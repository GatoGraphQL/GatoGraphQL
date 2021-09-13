<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

interface QueryableObjectTypeFieldSchemaDefinitionResolverInterface extends ObjectTypeFieldSchemaDefinitionResolverInterface
{
    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array;
}
