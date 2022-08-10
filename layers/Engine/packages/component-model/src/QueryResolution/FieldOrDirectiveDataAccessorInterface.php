<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;

interface FieldOrDirectiveDataAccessorInterface
{
    /**
     * @return string[]
     * @throws AbstractValueResolutionPromiseException
     */
    public function getProperties(): array;
    /**
     * @throws AbstractValueResolutionPromiseException
     */
    public function hasValue(string $propertyName): bool;
    /**
     * @throws AbstractValueResolutionPromiseException
     */
    public function getValue(string $propertyName): mixed;
}
