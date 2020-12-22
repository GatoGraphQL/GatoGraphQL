<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;

abstract class AbstractUseRootAsSourceForSchemaTypeResolver extends AbstractTypeResolver
{
    protected function getTypeResolverClassToCalculateSchema(): string
    {
        return RootTypeResolver::class;
    }

    abstract protected function isFieldNameConditionSatisfiedForSchema(FieldResolverInterface $fieldResolver, string $fieldName): bool;

    protected function isFieldNameResolvedByFieldResolver(FieldResolverInterface $fieldResolver, string $fieldName, array $fieldInterfaceResolverClasses): bool
    {
        if (!$this->isFieldNameConditionSatisfiedForSchema($fieldResolver, $fieldName)) {
            return false;
        }
        return parent::isFieldNameResolvedByFieldResolver($fieldResolver, $fieldName, $fieldInterfaceResolverClasses);
    }
}
