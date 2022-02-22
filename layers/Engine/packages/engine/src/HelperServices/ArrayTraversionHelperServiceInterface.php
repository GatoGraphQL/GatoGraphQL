<?php

declare(strict_types=1);

namespace PoP\Engine\HelperServices;

interface ArrayTraversionHelperServiceInterface
{
    /**
     * @throws RuntimeOperationException If the path cannot be reached under the array, or if its value is not an array
     */
    public function &getPointerToArrayItemUnderPath(array &$data, string $path): array;
    /**
     * @throws RuntimeOperationException If the path cannot be reached under the array
     */
    public function setValueToArrayItemUnderPath(array &$data, string $path, mixed $value): void;
}
