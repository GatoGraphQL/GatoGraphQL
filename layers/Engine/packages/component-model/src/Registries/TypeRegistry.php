<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;

class TypeRegistry implements TypeRegistryInterface
{
    /**
     * @var ObjectTypeResolverInterface[]
     */
    protected array $typeResolvers = [];

    public function addTypeResolver(ObjectTypeResolverInterface $typeResolver): void
    {
        $this->typeResolvers[] = $typeResolver;
    }
    /**
     * @return ObjectTypeResolverInterface[]
     */
    public function getTypeResolvers(): array
    {
        return $this->typeResolvers;
    }
}
