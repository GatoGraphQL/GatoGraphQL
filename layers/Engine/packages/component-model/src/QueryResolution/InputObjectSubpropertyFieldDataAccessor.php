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

    /**
     * @param array<string,mixed> $unresolvedFieldArgs
     */
    public function __construct(
        FieldInterface $field,
        protected string $inputObjectSubpropertyName,
        array $unresolvedFieldArgs,
    ) {
        parent::__construct($field, $unresolvedFieldArgs);
    }

    public function getInputObjectSubpropertyName(): string
    {
        return $this->inputObjectSubpropertyName;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUnresolvedFieldOrDirectiveArgs(): array
    {
        return (array) $this->getInputObjectValue(parent::getUnresolvedFieldOrDirectiveArgs());
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
