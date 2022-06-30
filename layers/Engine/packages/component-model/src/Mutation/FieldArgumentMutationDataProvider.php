<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

class FieldArgumentMutationDataProvider extends AbstractFieldArgumentMutationDataProvider
{
    public function hasValue(string $inputName): bool
    {
        return $this->field->hasArgument($inputName);
    }

    public function getValue(string $inputName): string
    {
        return $this->field->getArgumentValue($inputName);
    }
}
