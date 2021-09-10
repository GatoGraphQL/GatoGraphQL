<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\Object;

use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\Object\AbstractObjectTypeResolver;
use PoP\Engine\TypeResolvers\Object\RootTypeResolver;

abstract class AbstractUseRootAsSourceForSchemaTypeResolver extends AbstractObjectTypeResolver
{
    protected function getTypeResolverClassToCalculateSchema(): string
    {
        return RootTypeResolver::class;
    }

    abstract protected function isFieldNameConditionSatisfiedForSchema(
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        string $fieldName
    ): bool;

    protected function isFieldNameResolvedByFieldResolver(
        FieldResolverInterface $fieldResolver,
        string $fieldName,
        array $interfaceTypeResolverClasses,
    ): bool {
        if ($fieldResolver instanceof ObjectTypeFieldResolverInterface
            && !$this->isFieldNameConditionSatisfiedForSchema($fieldResolver, $fieldName)
        ) {
            return false;
        }
        return parent::isFieldNameResolvedByFieldResolver(
            $fieldResolver,
            $fieldName,
            $interfaceTypeResolverClasses
        );
    }
}
