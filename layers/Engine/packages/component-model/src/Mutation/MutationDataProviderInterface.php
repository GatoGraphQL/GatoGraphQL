<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

interface MutationDataProviderInterface
{
    /**
     * @return string[]
     */
    public function getPropertyNames(): array;
    public function hasProperty(string $propertyName): bool;
    public function getValue(string $propertyName): mixed;
}
