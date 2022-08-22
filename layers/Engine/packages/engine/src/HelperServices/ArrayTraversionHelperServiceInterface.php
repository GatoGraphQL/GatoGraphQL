<?php

declare(strict_types=1);

namespace PoP\Engine\HelperServices;

use PoP\Engine\Exception\RuntimeOperationException;

interface ArrayTraversionHelperServiceInterface
{
    /**
     * @return mixed[]
     * @throws RuntimeOperationException If the path cannot be reached under the array, or if its value is not an array
     * @param array<string,mixed> $data
     */
    public function &getPointerToArrayItemUnderPath(array &$data, string $path): array;
    /**
     * @throws RuntimeOperationException If the path cannot be reached under the array
     * @param array<string,mixed> $data
     */
    public function &getPointerToElementItemUnderPath(array &$data, string $path): mixed;
    /**
     * @throws RuntimeOperationException If the path cannot be reached under the array
     * @param array<string,mixed> $data
     */
    public function setValueToArrayItemUnderPath(array &$data, string $path, mixed $value): void;
}
