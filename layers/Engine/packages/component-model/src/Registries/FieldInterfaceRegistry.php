<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;

class FieldInterfaceRegistry implements FieldInterfaceRegistryInterface
{
    /**
     * @var FieldInterfaceResolverInterface[]
     */
    protected array $fieldInterfaceResolvers = [];

    public function addFieldInterfaceResolver(FieldInterfaceResolverInterface $fieldInterfaceResolver): void
    {
        $this->fieldInterfaceResolvers[] = $fieldInterfaceResolver;
    }
    /**
     * @return FieldInterfaceResolverInterface[]
     */
    public function getFieldInterfaceResolvers(): array
    {
        return $this->fieldInterfaceResolvers;
    }
}
