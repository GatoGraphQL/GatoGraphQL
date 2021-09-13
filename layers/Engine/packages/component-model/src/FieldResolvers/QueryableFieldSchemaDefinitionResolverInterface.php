<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

interface QueryableObjectTypeFieldSchemaDefinitionResolverInterface extends ObjectTypeFieldSchemaDefinitionResolverInterface
{
    public function getFieldDataFilteringModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array;
}
