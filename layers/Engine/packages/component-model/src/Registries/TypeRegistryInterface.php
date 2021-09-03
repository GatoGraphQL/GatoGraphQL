<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;

interface TypeRegistryInterface
{
    public function addTypeResolver(ObjectTypeResolverInterface $typeResolver): void;
    /**
     * @return ObjectTypeResolverInterface[]
     */
    public function getTypeResolvers(): array;
}
