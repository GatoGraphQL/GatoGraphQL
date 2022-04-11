<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

interface TypeRegistryInterface
{
    public function addTypeResolver(TypeResolverInterface $typeResolver): void;
    /**
     * @return TypeResolverInterface[]
     */
    public function getTypeResolvers(): array;
    /**
     * @return RelationalTypeResolverInterface[]
     */
    public function getRelationalTypeResolvers(): array;
    /**
     * @return UnionTypeResolverInterface[]
     */
    public function getUnionTypeResolvers(): array;
    /**
     * @return ObjectTypeResolverInterface[]
     */
    public function getObjectTypeResolvers(): array;
    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getInterfaceTypeResolvers(): array;
}
