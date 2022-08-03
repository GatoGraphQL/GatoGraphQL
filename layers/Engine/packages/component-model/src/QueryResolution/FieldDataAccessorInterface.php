<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Exception\AbstractDeferredValuePromiseException;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface FieldDataAccessorInterface
{
    public function getField(): FieldInterface;
    public function getFieldName(): string;
    /**
     * @return array<string,mixed>
     * @throws AbstractDeferredValuePromiseException
     */
    public function getFieldArgs(): array;
    /**
     * @return string[]
     * @throws AbstractDeferredValuePromiseException
     */
    public function getProperties(): array;
    /**
     * @throws AbstractDeferredValuePromiseException
     */
    public function hasValue(string $propertyName): bool;
    /**
     * @throws AbstractDeferredValuePromiseException
     */
    public function getValue(string $propertyName): mixed;
}
