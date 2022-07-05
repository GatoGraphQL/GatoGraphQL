<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class FieldDataAccessor implements FieldDataAccessorInterface
{
    public function __construct(
        protected FieldInterface $field,
        /** @var array<string,mixed> */
        protected array $normalizedValues = [],
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
        return array_unique(array_merge(
            $this->getPropertiesInField(),
            $this->getPropertiesInNormalizedValues(),
        ));
    }

    /**
     * @return string[]
     */
    protected function getPropertiesInField(): array
    {
        return array_map(
            fn (Argument $argument) => $argument->getName(),
            $this->field->getArguments()
        );
    }

    /**
     * @return string[]
     */
    protected function getPropertiesInNormalizedValues(): array
    {
        return array_keys($this->normalizedValues);
    }

    /**
     * @return array<string,mixed>
     */
    public function getKeyValues(): array
    {
        return array_merge(
            $this->getKeyValuesInField(),
            $this->getKeyValuesInNormalizedValues(),
        );
    }

    /**
     * @return string[]
     */
    protected function getKeyValuesInField(): array
    {
        return $this->field->getArgumentKeyValues();
    }

    /**
     * @return string[]
     */
    protected function getKeyValuesInNormalizedValues(): array
    {
        return $this->normalizedValues;
    }

    public function hasValue(string $propertyName): bool
    {
        return $this->hasValueInField($propertyName)
            || $this->hasValueInNormalizedValues($propertyName);
    }

    protected function hasValueInField(string $propertyName): bool
    {
        return $this->field->hasArgument($propertyName);
    }

    protected function hasValueInNormalizedValues(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->normalizedValues);
    }

    public function getValue(string $propertyName): mixed
    {
        if ($this->hasValueInField($propertyName)) {
            return $this->getValueFromField($propertyName);
        }
        return $this->getValueFromCustomValues($propertyName);
    }

    protected function getValueFromField(string $propertyName): mixed
    {
        return $this->field->getArgumentValue($propertyName);
    }

    protected function getValueFromCustomValues(string $propertyName): mixed
    {
        return $this->normalizedValues[$propertyName] ?? null;
    }

    // @todo Remove this function
    public function addValue(string $propertyName, mixed $value): void
    {
        $this->normalizedValues[$propertyName] = $value;
    }
}
