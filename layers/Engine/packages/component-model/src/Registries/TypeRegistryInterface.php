<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface TypeRegistryInterface
{
    public function addTypeResolver(TypeResolverInterface $typeResolver): void;
    /**
     * @return TypeResolverInterface[]
     */
    public function getTypeResolvers(): array;
}
