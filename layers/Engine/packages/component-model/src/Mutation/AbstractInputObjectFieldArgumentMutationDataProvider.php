<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use stdClass;

abstract class AbstractInputObjectFieldArgumentMutationDataProvider extends AbstractFieldArgumentMutationDataProvider implements InputObjectFieldArgumentMutationDataProviderInterface
{
    /**
     * @return string[]
     */
    public function getPropertyNames(): array
    {
        $inputObjectValue = $this->getInputObjectValue();
        return array_unique(array_merge(
            parent::getPropertyNames(),
            array_keys((array) $inputObjectValue)
        ));
    }

    public function hasProperty(string $propertyName): bool
    {
        $inputObjectValue = $this->getInputObjectValue();
        if (property_exists($inputObjectValue, $propertyName)) {
            return true;
        }
        return parent::hasProperty($propertyName);
    }

    public function getValue(string $propertyName): mixed
    {
        $inputObjectValue = $this->getInputObjectValue();
        if (property_exists($inputObjectValue, $propertyName)) {
            return $inputObjectValue->$propertyName;
        }
        return parent::getValue($propertyName);
    }

    protected function getInputObjectValue(): stdClass
    {
        return $this->getInputObject()->getValue();
    }

    final protected function getInputObject(): InputObject
    {
        $argument = $this->field->getArgument($this->getArgumentName());
        return $argument->getValueAST();
    }
}
