<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\QueryableFieldInterfaceSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldResolvers\QueryableFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class QueryableInterfaceSchemaDefinitionResolverAdapter extends InterfaceSchemaDefinitionResolverAdapter implements QueryableFieldSchemaDefinitionResolverInterface
{
    public function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        /** @var QueryableFieldInterfaceSchemaDefinitionResolverInterface */
        $fieldInterfaceResolver = $this->fieldInterfaceResolver;
        return $fieldInterfaceResolver->getFieldDataFilteringModule($fieldName);
    }
}
