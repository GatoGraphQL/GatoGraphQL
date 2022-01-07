<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Error;

interface ErrorServiceInterface
{
    /**
     * @param string[]|null $path
     * @return array<string, mixed>
     */
    public function getErrorOutput(Error $error, ?array $path = null, ?string $argName = null): array;
}
