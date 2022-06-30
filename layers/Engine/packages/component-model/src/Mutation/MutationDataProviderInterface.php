<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

interface MutationDataProviderInterface
{
    public function hasProperty(string $propertyName): bool;
    public function getValue(string $propertyName): mixed;
}
