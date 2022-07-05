<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface FieldDataAccessorInterface
{
    public function getField(): FieldInterface;
    public function getFieldName(): string;
    /**
     * @return string[]
     */
    public function getProperties(): array;
    public function hasValue(string $propertyName): bool;
    public function getValue(string $propertyName): mixed;
    public function addValue(string $propertyName, mixed $value): void;
}
