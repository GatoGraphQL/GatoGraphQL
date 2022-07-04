<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface FieldDataProviderInterface
{
    public function getField(): FieldInterface;
    /**
     * @return string[]
     */
    public function getProperties(): array;
    public function has(string $propertyName): bool;
    public function get(string $propertyName): mixed;
    public function add(string $propertyName, mixed $value): void;
}
