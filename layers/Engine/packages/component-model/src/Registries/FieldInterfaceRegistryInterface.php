<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;

interface FieldInterfaceRegistryInterface
{
    public function addFieldInterfaceResolver(FieldInterfaceResolverInterface $fieldInterfaceResolver): void;
    /**
     * @return FieldInterfaceResolverInterface[]
     */
    public function getFieldInterfaceResolvers(): array;
}
