<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Error;

use stdClass;

interface ErrorServiceInterface
{
    /**
     * Encode the array, and trim to 500 chars max
     *
     * @param mixed[] $value
     */
    public function jsonEncodeArrayOrStdClassValue(array|stdClass $value): string;
    
    /**
     * @param string[]|null $path
     * @return array<string, mixed>
     */
    public function getErrorOutput(Error $error, ?array $path = null, ?string $argName = null): array;
}
