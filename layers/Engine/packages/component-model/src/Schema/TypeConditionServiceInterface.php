<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

/**
 * Single place defining if an object type satisfies
 * a type condition (as in fragment `... on SomeType`),
 * which can be the object type itself, an interface
 * it implements, or a union type it belongs to.
 */
interface TypeConditionServiceInterface
{
    public function isObjectType(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $typeName,
    ): bool;

    public function implementsInterface(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $interfaceName,
    ): bool;

    public function getUnionTypeResolver(string $unionTypeName): ?UnionTypeResolverInterface;

    /**
     * Whether the object type is, implements, or is part
     * of (as a union) the type in the type condition.
     */
    public function isTypeOrImplements(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $typeOrInterface,
    ): bool;
}
