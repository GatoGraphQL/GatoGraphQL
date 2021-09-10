<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\FieldInterfaceResolvers\InterfaceTypeFieldResolverInterface;

class FieldInterfaceRegistry implements FieldInterfaceRegistryInterface
{
    /**
     * @var InterfaceTypeFieldResolverInterface[]
     */
    protected array $fieldInterfaceResolvers = [];

    public function addFieldInterfaceResolver(InterfaceTypeFieldResolverInterface $fieldInterfaceResolver): void
    {
        $this->fieldInterfaceResolvers[] = $fieldInterfaceResolver;
    }
    /**
     * @return InterfaceTypeFieldResolverInterface[]
     */
    public function getFieldInterfaceResolvers(): array
    {
        return $this->fieldInterfaceResolvers;
    }
}
