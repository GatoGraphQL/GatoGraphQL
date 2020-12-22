<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

class FieldInterfaceRegistry implements FieldInterfaceRegistryInterface
{
    /**
     * @var string[]
     */
    protected array $fieldInterfaceResolverClasses = [];

    public function addFieldInterfaceResolverClass(string $fieldInterfaceResolverClass): void
    {
        $this->fieldInterfaceResolverClasses[] = $fieldInterfaceResolverClass;
    }
    public function getFieldInterfaceResolverClasses(): array
    {
        return $this->fieldInterfaceResolverClasses;
    }
}
