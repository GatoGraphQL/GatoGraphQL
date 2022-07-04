<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use stdClass;

class PropertyUnderInputObjectUnderFieldArgumentFieldDataAccessor extends InputObjectUnderFieldArgumentFieldDataAccessor implements PropertyUnderInputObjectUnderFieldArgumentFieldDataAccessorInterface
{
    public function __construct(
        FieldInterface $field,
        string $fieldInputArgumentName,
        protected string $inputObjectPropertyName,
        array $customValues = [],
    ) {
        parent::__construct($field, $fieldInputArgumentName, $customValues);
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
