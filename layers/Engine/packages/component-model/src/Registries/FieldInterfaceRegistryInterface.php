<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\FieldInterfaceResolvers\InterfaceTypeFieldResolverInterface;

interface FieldInterfaceRegistryInterface
{
    public function addFieldInterfaceResolver(InterfaceTypeFieldResolverInterface $fieldInterfaceResolver): void;
    /**
     * @return InterfaceTypeFieldResolverInterface[]
     */
    public function getFieldInterfaceResolvers(): array;
}
