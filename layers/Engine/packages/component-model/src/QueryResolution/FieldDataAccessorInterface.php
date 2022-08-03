<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Exception\Parser\DeferredValuePromiseExceptionInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface FieldDataAccessorInterface
{
    public function getField(): FieldInterface;
    public function getFieldName(): string;
    /**
     * @return string[]
     */
    public function getProperties(): array;
    /**
     * @return array<string,mixed>
     * @throws DeferredValuePromiseExceptionInterface When accessing non-declared Dynamic Variables
     */
    public function getKeyValues(): array;
    public function hasValue(string $propertyName): bool;
    public function getValue(string $propertyName): mixed;
}
