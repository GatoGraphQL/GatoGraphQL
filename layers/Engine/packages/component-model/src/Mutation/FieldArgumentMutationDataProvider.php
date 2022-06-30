<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

class FieldArgumentMutationDataProvider extends AbstractFieldArgumentMutationDataProvider
{
    public function getValue(string $inputName): string
    {
        return $this->field->getArgumentValue($inputName);
    }
}
