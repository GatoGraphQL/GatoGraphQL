<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\QueryableFieldInterfaceSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldResolvers\QueryableFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;

class QueryableInterfaceSchemaDefinitionResolverAdapter extends InterfaceSchemaDefinitionResolverAdapter implements QueryableFieldSchemaDefinitionResolverInterface
{
    public function getFieldDataFilteringModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        /** @var QueryableFieldInterfaceSchemaDefinitionResolverInterface */
        $fieldInterfaceSchemaDefinitionResolver = $this->fieldInterfaceSchemaDefinitionResolver;
        return $fieldInterfaceSchemaDefinitionResolver->getFieldDataFilteringModule($fieldName);
    }
}
