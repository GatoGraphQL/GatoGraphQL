<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

interface TypeRegistryInterface
{
    public function addTypeResolverClass(string $typeResolverClass): void;
    public function getTypeResolverClasses(): array;
}
