<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

abstract class AbstractMutationDataProvider implements MutationDataProviderInterface
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
    public function hasProperty(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->propertyValues);
    }
    public function getValue(string $propertyName): mixed
    {
        return $this->propertyValues[$propertyName] ?? null;
    }
}
