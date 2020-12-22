<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

class TypeRegistry implements TypeRegistryInterface
{
    /**
     * @var string[]
     */
    protected array $typeResolverClasses = [];

    public function addTypeResolverClass(string $typeResolverClass): void
    {
        $this->typeResolverClasses[] = $typeResolverClass;
    }
    public function getTypeResolverClasses(): array
    {
        return $this->typeResolverClasses;
    }
}
