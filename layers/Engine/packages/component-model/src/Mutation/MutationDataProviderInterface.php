<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

interface MutationDataProviderInterface
{
    public function hasValue(string $inputName): bool;
    public function getValue(string $inputName): string;
}
