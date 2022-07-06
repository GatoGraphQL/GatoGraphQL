<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class FieldDataAccessor implements FieldDataAccessorInterface
{
    public function __construct(
        protected FieldInterface $field,
        /** @var array<string,mixed> */
        protected array $normalizedValues,
    ) {
    }

    public function getField(): FieldInterface
    {
        return $this->field;
    }

    final public function getFieldName(): string
    {
        return $this->field->getName();
    }

    /**
     * @return string[]
     */
    public function getProperties(): array
    {
        return array_keys($this->getKeyValuesSource());
    }

    /**
     * @return array<string,mixed>
     */
    protected function getKeyValuesSource(): array
    {
        return $this->normalizedValues;
    }

    /**
     * @return array<string,mixed>
     */
    public function getKeyValues(): array
    {
        return $this->getKeyValuesSource();
    }

    public function hasValue(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->getKeyValuesSource());
    }

    public function getValue(string $propertyName): mixed
    {
        return $this->getKeyValuesSource()[$propertyName] ?? null;
    }
}
