<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use stdClass;

abstract class AbstractInputObjectFieldArgumentMutationDataProvider extends AbstractFieldArgumentMutationDataProvider
{
    public function hasValue(string $inputName): bool
    {
        $inputObjectValue = $this->getInputObjectValue();
        return property_exists($inputObjectValue, $inputName);
    }

    public function getValue(string $inputName): string
    {
        $inputObjectValue = $this->getInputObjectValue();
        return $inputObjectValue->$inputName;
    }

    protected function getInputObjectValue(): stdClass
    {
        return $this->getInputObject()->getValue();
    }

    protected function getInputObject(): InputObject
    {
        $argument = $this->field->getArgument($this->getArgumentName());
        return $argument->getValueAST();
    }

    abstract protected function getArgumentName(): string;
}
