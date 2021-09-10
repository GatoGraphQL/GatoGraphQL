<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\FieldInterfaceResolvers\InterfaceTypeFieldResolverInterface;

class FieldInterfaceRegistry implements FieldInterfaceRegistryInterface
{
    /**
     * @var InterfaceTypeFieldResolverInterface[]
     */
    protected array $interfaceTypeFieldResolvers = [];

    public function addFieldInterfaceResolver(InterfaceTypeFieldResolverInterface $interfaceTypeFieldResolver): void
    {
        $this->fieldInterfaceResolvers[] = $interfaceTypeFieldResolver;
    }
    /**
     * @return InterfaceTypeFieldResolverInterface[]
     */
    public function getFieldInterfaceResolvers(): array
    {
        return $this->fieldInterfaceResolvers;
    }
}
