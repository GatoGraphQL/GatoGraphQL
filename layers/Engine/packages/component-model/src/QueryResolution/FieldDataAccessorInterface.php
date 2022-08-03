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
     * @return array<string,mixed>
     * @throws DeferredValuePromiseExceptionInterface
     */
    public function getFieldArgs(): array;
    /**
     * @return array<string,mixed>
     */
    public function getUnresolvedFieldArgs(): array;
    /**
     * @return string[]
     * @throws DeferredValuePromiseExceptionInterface
     */
    public function getProperties(): array;
    /**
     * @throws DeferredValuePromiseExceptionInterface
     */
    public function hasValue(string $propertyName): bool;
    /**
     * @throws DeferredValuePromiseExceptionInterface
     */
    public function getValue(string $propertyName): mixed;
}
