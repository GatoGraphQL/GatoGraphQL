<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use stdClass;

abstract class AbstractInputObjectFieldArgumentMutationDataProvider extends AbstractFieldArgumentMutationDataProvider
{
    /**
     * @return string[]
     */
    public function getPropertyNames(): array
    {
        $inputObjectValue = $this->getInputObjectValue();
        return array_keys((array) $inputObjectValue);
    }

    public function hasProperty(string $propertyName): bool
    {
        $inputObjectValue = $this->getInputObjectValue();
        return property_exists($inputObjectValue, $propertyName);
    }

    public function getValue(string $propertyName): mixed
    {
        if (!$this->hasProperty($propertyName)) {
            return null;
        }
        $inputObjectValue = $this->getInputObjectValue();
        return $inputObjectValue->$propertyName;
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
