<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface TypeRegistryInterface
{
    public function addTypeResolver(RelationalTypeResolverInterface $typeResolver): void;
    /**
     * @return RelationalTypeResolverInterface[]
     */
    public function getTypeResolvers(): array;
}
