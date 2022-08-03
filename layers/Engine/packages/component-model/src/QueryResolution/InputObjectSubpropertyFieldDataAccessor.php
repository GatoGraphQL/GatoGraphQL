<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Exception\Parser\InvalidRuntimeVariableReferenceException;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Services\StandaloneServiceTrait;
use stdClass;

class InputObjectSubpropertyFieldDataAccessor extends FieldDataAccessor implements InputObjectSubpropertyFieldDataAccessorInterface
{
    use StandaloneServiceTrait;

    public function __construct(
        FieldInterface $field,
        protected string $inputObjectSubpropertyName,
        array $fieldArgs,
    ) {
        parent::__construct($field, $fieldArgs);
    }

    public function getInputObjectSubpropertyName(): string
    {
        return $this->inputObjectSubpropertyName;
    }

    /**
     * @return array<string,mixed>
     * @throws InvalidRuntimeVariableReferenceException
     */
    protected function getKeyValuesSource(): array
    {
        return (array) $this->getInputObjectValue(parent::getKeyValuesSource());
    }

    /**
     * @param array<string,mixed> $fieldArgs
     * @throws ShouldNotHappenException If the argument value under the provided inputName is not an InputObject
     */
    private function getInputObjectValue(array $fieldArgs): stdClass
    {
        $inputObjectSubpropertyName = $this->getInputObjectSubpropertyName();
        $inputObjectValue = $fieldArgs[$inputObjectSubpropertyName];
        if (!($inputObjectValue instanceof stdClass)) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__(
                        'Input value under argument \'%s\' is not an InputObject type'
                    ),
                    $inputObjectSubpropertyName
                )
            );
        }
        return $inputObjectValue;
    }
}
