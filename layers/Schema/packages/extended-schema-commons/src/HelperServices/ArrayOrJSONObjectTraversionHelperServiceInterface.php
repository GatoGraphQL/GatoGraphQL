<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\HelperServices;

use PoP\Engine\Exception\RuntimeOperationException;
use stdClass;

interface ArrayOrJSONObjectTraversionHelperServiceInterface
{
    /**
     * @throws RuntimeOperationException If the path cannot be reached under the array
     * @param array<string|int,mixed>|stdClass $data
     */
    public function &getPointerToArrayItemOrObjectPropertyUnderPath(array|stdClass &$data, int|string $path): mixed;
    /**
     * @throws RuntimeOperationException If the path cannot be reached under the array
     * @param array<string|int,mixed>|stdClass $data
     */
    public function setValueToArrayItemOrObjectPropertyUnderPath(array|stdClass &$data, int|string $path, mixed $value): void;
}
