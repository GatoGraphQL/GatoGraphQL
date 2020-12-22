<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

interface FieldInterfaceRegistryInterface
{
    public function addFieldInterfaceResolverClass(string $fieldInterfaceResolverClass): void;
    public function getFieldInterfaceResolverClasses(): array;
}
