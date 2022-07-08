<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Services\StandaloneServiceTrait;
use stdClass;

class SubpropertyFieldDataAccessor extends FieldDataAccessor implements SubpropertyFieldDataAccessorInterface
{
    use StandaloneServiceTrait;

    public function __construct(
        FieldInterface $field,
        protected string $fieldInputArgumentName,
        array $normalizedValues,
    ) {
        parent::__construct($field, $normalizedValues);
    }

    public function getSubpropertyName(): string
    {
        return $this->fieldInputArgumentName;
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
        $inputObjectValue = $this->normalizedValues[$this->getSubpropertyName()];
        if (!($inputObjectValue instanceof stdClass)) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__(
                        'Input value under argument \'%s\' is not an InputObject type'
                    ),
                    $this->getSubpropertyName()
                )
            );
        }
        return $inputObjectValue;
    }
}
