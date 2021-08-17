<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\QueryableSchemaFieldInterfaceResolverInterface;
use PoP\ComponentModel\FieldResolvers\QueryableFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class QueryableInterfaceSchemaDefinitionResolverAdapter extends InterfaceSchemaDefinitionResolverAdapter implements QueryableFieldSchemaDefinitionResolverInterface
{
    public function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        /** @var QueryableSchemaFieldInterfaceResolverInterface */
        $fieldInterfaceResolver = $this->fieldInterfaceResolver;
        return $fieldInterfaceResolver->getFieldDataFilteringModule($fieldName);
    }
}
