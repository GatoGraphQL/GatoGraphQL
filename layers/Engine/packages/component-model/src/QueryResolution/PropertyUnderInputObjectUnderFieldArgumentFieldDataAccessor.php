<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Exception\ShouldNotHappenException;
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

    /**
     * @throws ShouldNotHappenException If the argument value under the provided inputName is not an InputObject
     */
    protected function getInputObjectValue(): stdClass
    {
        $inputObjectValue = parent::getInputObjectValue();
        $inputObjectUnderPropertyValue = $inputObjectValue->{$this->getInputObjectPropertyName()};
        if (!($inputObjectUnderPropertyValue instanceof stdClass)) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__(
                        'Input value under argument \'%s\' and property \'%s\' is not an InputObject type'
                    ),
                    $this->getArgumentName(),
                    $this->getInputObjectPropertyName()
                )
            );
        }
        return $inputObjectUnderPropertyValue;
    }
}
