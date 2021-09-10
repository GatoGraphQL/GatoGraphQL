<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\Object;

use PoP\ComponentModel\FieldInterfaceResolvers\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\Interface\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\Object\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;
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
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        string $fieldName,
        array $interfaceTypeResolverClasses,
    ): bool {
        if (
            $objectTypeOrInterfaceTypeFieldResolver instanceof ObjectTypeFieldResolverInterface
            && !$this->isFieldNameConditionSatisfiedForSchema($objectTypeOrInterfaceTypeFieldResolver, $fieldName)
        ) {
            return false;
        }
        return parent::isFieldNameResolvedByFieldResolver(
            $objectTypeOrInterfaceTypeResolver,
            $objectTypeOrInterfaceTypeFieldResolver,
            $fieldName,
            $interfaceTypeResolverClasses
        );
    }
}
