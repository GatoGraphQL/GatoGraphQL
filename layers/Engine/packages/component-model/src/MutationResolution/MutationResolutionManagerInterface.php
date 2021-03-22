<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolution;

interface MutationResolutionManagerInterface
{
    public function clearResults(): void;

    public function setResult(string $class, mixed $result): void;

    public function getResult(string $class): mixed;
}
