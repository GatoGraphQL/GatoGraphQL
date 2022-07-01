<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use stdClass;

class PropertyUnderInputObjectUnderFieldArgumentMutationDataProvider extends InputObjectUnderFieldArgumentMutationDataProvider implements PropertyUnderInputObjectUnderFieldArgumentMutationDataProviderInterface
{
    public function __construct(
        FieldInterface $field,
        string $fieldInputArgumentName,
        protected string $inputObjectPropertyName,
        array $propertyValues = [],
    ) {
        parent::__construct($field, $fieldInputArgumentName, $propertyValues);
    }

    public function getInputObjectPropertyName(): string
    {
        return $this->inputObjectPropertyName;
    }

    protected function getInputObjectValue(): stdClass
    {
        $inputObjectValue = parent::getInputObjectValue();
        return $inputObjectValue->{$this->getInputObjectPropertyName()};
    }
}
