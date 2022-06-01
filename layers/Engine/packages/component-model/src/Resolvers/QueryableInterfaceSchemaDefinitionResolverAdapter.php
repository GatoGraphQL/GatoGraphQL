<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\QueryableObjectTypeFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class QueryableInterfaceSchemaDefinitionResolverAdapter extends InterfaceSchemaDefinitionResolverAdapter implements QueryableObjectTypeFieldSchemaDefinitionResolverInterface
{
    public function getFieldFilterInputContainerComponent(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?Component
    {
        /** @var QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface */
        $interfaceTypeFieldSchemaDefinitionResolver = $this->interfaceTypeFieldSchemaDefinitionResolver;
        return $interfaceTypeFieldSchemaDefinitionResolver->getFieldFilterInputContainerComponent($fieldName);
    }
}
