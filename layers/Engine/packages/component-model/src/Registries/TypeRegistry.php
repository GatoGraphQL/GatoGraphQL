<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TypeRegistry implements TypeRegistryInterface
{
    /**
     * @var TypeResolverInterface[]
     */
    protected array $typeResolvers = [];

    public function addTypeResolver(TypeResolverInterface $typeResolver): void
    {
        $this->typeResolvers[] = $typeResolver;
    }
    /**
     * @return TypeResolverInterface[]
     */
    public function getTypeResolvers(): array
    {
        return $this->typeResolvers;
    }
    /**
     * @return RelationalTypeResolverInterface[]
     */
    public function getRelationalTypeResolvers(): array
    {
        return array_values(array_filter(
            $this->typeResolvers,
            fn ($typeResolver) => $typeResolver instanceof RelationalTypeResolverInterface
        ));
    }
    /**
     * @return UnionTypeResolverInterface[]
     */
    public function getUnionTypeResolvers(): array
    {
        return array_values(array_filter(
            $this->typeResolvers,
            fn ($typeResolver) => $typeResolver instanceof UnionTypeResolverInterface
        ));
    }
    /**
     * @return ObjectTypeResolverInterface[]
     */
    public function getObjectTypeResolvers(): array
    {
        return array_values(array_filter(
            $this->typeResolvers,
            fn ($typeResolver) => $typeResolver instanceof ObjectTypeResolverInterface
        ));
    }
    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getInterfaceTypeResolvers(): array
    {
        return array_values(array_filter(
            $this->typeResolvers,
            fn ($typeResolver) => $typeResolver instanceof InterfaceTypeResolverInterface
        ));
    }
    /**
     * @return EnumTypeResolverInterface[]
     */
    public function getEnumTypeResolvers(): array
    {
        return array_values(array_filter(
            $this->typeResolvers,
            fn ($typeResolver) => $typeResolver instanceof EnumTypeResolverInterface
        ));
    }
    /**
     * @return ScalarTypeResolverInterface[]
     */
    public function getScalarTypeResolvers(): array
    {
        return array_values(array_filter(
            $this->typeResolvers,
            fn ($typeResolver) => $typeResolver instanceof ScalarTypeResolverInterface
        ));
    }
}
