<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

class FieldDataProvider implements FieldDataProviderInterface
{
    public function __construct(
        /** @var array<string,mixed> */
        protected array $propertyValues = [],
    ) {
    }

    /**
     * @return string[]
     */
    public function getPropertyNames(): array
    {
        return array_keys($this->propertyValues);
    }
    public function has(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->propertyValues);
    }
    public function get(string $propertyName): mixed
    {
        return $this->propertyValues[$propertyName] ?? null;
    }
    public function add(string $propertyName, mixed $value): void
    {
        $this->propertyValues[$propertyName] = $value;
    }
}
