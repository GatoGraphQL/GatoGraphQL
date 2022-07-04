<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class FieldDataProvider implements FieldDataProviderInterface
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
            fn(Argument $argument) => $argument->getName(),
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

    public function has(string $propertyName): bool
    {
        return $this->hasInField($propertyName)
            || $this->hasInCustomValues($propertyName);
    }

    protected function hasInField(string $propertyName): bool
    {
        return $this->field->hasArgument($propertyName);
    }

    protected function hasInCustomValues(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->customValues);
    }

    public function get(string $propertyName): mixed
    {
        if ($this->hasInField($propertyName)) {
            return $this->getFromField($propertyName);
        }
        return $this->getFromCustomValues($propertyName);
    }

    protected function getFromField(string $propertyName): mixed
    {
        return $this->field->getArgumentValue($propertyName);
    }

    protected function getFromCustomValues(string $propertyName): mixed
    {
        return $this->customValues[$propertyName] ?? null;
    }

    public function add(string $propertyName, mixed $value): void
    {
        $this->customValues[$propertyName] = $value;
    }
}
