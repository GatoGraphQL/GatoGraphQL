<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

class FieldArgumentMutationDataProvider extends AbstractFieldArgumentMutationDataProvider
{
    public function hasProperty(string $propertyName): bool
    {
        return $this->field->hasArgument($propertyName);
    }

    public function getValue(string $propertyName): mixed
    {
        return $this->field->getArgumentValue($propertyName);
    }
}
