<?php

declare(strict_types=1);

namespace PoP\Engine\HelperServices;

use PoP\Engine\Exception\RuntimeOperationException;

interface ArrayTraversionHelperServiceInterface
{
    /**
     * @return mixed[]
     * @throws RuntimeOperationException If the path cannot be reached under the array, or if its value is not an array
     * @param array<string|int,mixed> $data
     */
    public function &getPointerToArrayItemUnderPath(array &$data, int|string $path): array;
    /**
     * @throws RuntimeOperationException If the path cannot be reached under the array
     * @param array<string|int,mixed> $data
     */
    public function &getPointerToElementItemUnderPath(array &$data, int|string $path): mixed;
    /**
     * @throws RuntimeOperationException If the path cannot be reached under the array
     * @param array<string|int,mixed> $data
     */
    public function setValueToArrayItemUnderPath(array &$data, int|string $path, mixed $value): void;
}
