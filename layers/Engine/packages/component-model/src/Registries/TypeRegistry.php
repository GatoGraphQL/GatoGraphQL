<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

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
}
