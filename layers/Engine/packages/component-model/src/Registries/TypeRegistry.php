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

    public function addTypeResolver(RelationalTypeResolverInterface $relationalTypeResolver): void
    {
        $this->typeResolvers[] = $relationalTypeResolver;
    }
    /**
     * @return RelationalTypeResolverInterface[]
     */
    public function getTypeResolvers(): array
    {
        return $this->typeResolvers;
    }
}
