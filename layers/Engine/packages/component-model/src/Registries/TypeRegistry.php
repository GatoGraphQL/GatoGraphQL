<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class TypeRegistry implements TypeRegistryInterface
{
    /**
     * @var RelationalTypeResolverInterface[]
     */
    protected array $typeResolvers = [];

    public function addTypeResolver(RelationalTypeResolverInterface $typeResolver): void
    {
        $this->typeResolvers[] = $typeResolver;
    }
    /**
     * @return RelationalTypeResolverInterface[]
     */
    public function getTypeResolvers(): array
    {
        return $this->typeResolvers;
    }
}
