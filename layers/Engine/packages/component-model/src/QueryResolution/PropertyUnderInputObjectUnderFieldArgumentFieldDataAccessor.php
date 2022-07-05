<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use stdClass;

class PropertyUnderInputObjectUnderFieldArgumentFieldDataAccessor extends InputObjectUnderFieldArgumentFieldDataAccessor implements PropertyUnderInputObjectUnderFieldArgumentFieldDataAccessorInterface
{
    public function __construct(
        FieldInterface $field,
        string $fieldInputArgumentName,
        protected string $inputObjectPropertyName,
        array $normalizedValues = [],
    ) {
        parent::__construct($field, $fieldInputArgumentName, $normalizedValues);
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
