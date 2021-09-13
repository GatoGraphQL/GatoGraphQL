<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\InterfaceTypeFieldResolvers\QueryableInterfaceTypeObjectTypeFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldResolvers\QueryableObjectTypeFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class QueryableInterfaceSchemaDefinitionResolverAdapter extends InterfaceSchemaDefinitionResolverAdapter implements QueryableObjectTypeFieldSchemaDefinitionResolverInterface
{
    public function getFieldDataFilteringModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        /** @var QueryableInterfaceTypeObjectTypeFieldSchemaDefinitionResolverInterface */
        $interfaceTypeFieldSchemaDefinitionResolver = $this->interfaceTypeFieldSchemaDefinitionResolver;
        return $interfaceTypeFieldSchemaDefinitionResolver->getFieldDataFilteringModule($fieldName);
    }
}
