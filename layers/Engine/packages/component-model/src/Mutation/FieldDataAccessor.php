<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class FieldDataAccessor implements FieldDataAccessorInterface
{
    public function __construct(
        protected FieldInterface $field,
        /** @var array<string,mixed> */
        protected array $customValues = [],
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
            $this->getPropertiesInCustomValues(),
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
    protected function getPropertiesInCustomValues(): array
    {
        return array_keys($this->customValues);
    }

    public function hasValue(string $propertyName): bool
    {
        return $this->hasValueInField($propertyName)
            || $this->hasValueInCustomValues($propertyName);
    }

    protected function hasValueInField(string $propertyName): bool
    {
        return $this->field->hasArgument($propertyName);
    }

    protected function hasValueInCustomValues(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->customValues);
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
        return $this->customValues[$propertyName] ?? null;
    }

    public function addValue(string $propertyName, mixed $value): void
    {
        $this->customValues[$propertyName] = $value;
    }
}
