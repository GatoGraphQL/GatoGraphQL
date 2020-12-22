<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolution;

interface MutationResolutionManagerInterface
{
    public function clearResults(): void;

    /**
     * @param mixed $result
     */
    public function setResult(string $class, $result): void;

    /**
     * @return mixed
     */
    public function getResult(string $class);
}

