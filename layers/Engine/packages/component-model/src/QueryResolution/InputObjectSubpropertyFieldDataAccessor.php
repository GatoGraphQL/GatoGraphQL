<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

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
     */
    protected function getKeyValuesSource(): array
    {
        return (array) $this->getInputObjectValue();
    }

    /**
     * @throws ShouldNotHappenException If the argument value under the provided inputName is not an InputObject
     */
    protected function getInputObjectValue(): stdClass
    {
        $inputObjectValue = $this->fieldArgs[$this->getInputObjectSubpropertyName()];
        if (!($inputObjectValue instanceof stdClass)) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__(
                        'Input value under argument \'%s\' is not an InputObject type'
                    ),
                    $this->getInputObjectSubpropertyName()
                )
            );
        }
        return $inputObjectValue;
    }
}
