<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Services\StandaloneServiceTrait;
use stdClass;

class InputObjectListItemSubpropertyFieldDataAccessor extends FieldDataAccessor implements InputObjectListItemSubpropertyFieldDataAccessorInterface
{
    use StandaloneServiceTrait;

    /**
     * @param array<string,mixed> $unresolvedFieldArgs
     */
    public function __construct(
        FieldInterface $field,
        protected string $inputObjectListSubpropertyName,
        protected int $inputObjectListItemPosition,
        array $unresolvedFieldArgs,
    ) {
        parent::__construct($field, $unresolvedFieldArgs);
    }

    public function getInputObjectListSubpropertyName(): string
    {
        return $this->inputObjectListSubpropertyName;
    }

    public function getInputObjectListItemPosition(): int
    {
        return $this->inputObjectListItemPosition;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUnresolvedFieldOrDirectiveArgs(): array
    {
        return (array) $this->getInputObjectListeItemValue(parent::getUnresolvedFieldOrDirectiveArgs());
    }

    /**
     * @param array<string,mixed> $fieldArgs
     * @throws ShouldNotHappenException If the argument value under the provided inputName and itemListPosition is not an InputObject
     */
    private function getInputObjectListeItemValue(array $fieldArgs): stdClass
    {
        $inputObjectListSubpropertyName = $this->getInputObjectListSubpropertyName();
        $inputObjectListValue = $fieldArgs[$inputObjectListSubpropertyName];
        if (!is_array($inputObjectListValue)) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__(
                        'Input value under argument \'%s\' is not a List type'
                    ),
                    $inputObjectListSubpropertyName
                )
            );
        }
        /** @var mixed[] $inputObjectListValue */
        $inputObjectListItemPosition = $this->getInputObjectListItemPosition();
        if (!array_key_exists($inputObjectListItemPosition, $inputObjectListValue)) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__(
                        'Input list value under argument \'%s\' has no item on position \'%s\''
                    ),
                    $inputObjectListSubpropertyName,
                    $inputObjectListItemPosition
                )
            );
        }
        $inputObjectListItemValue = $inputObjectListValue[$inputObjectListItemPosition];
        if (!($inputObjectListItemValue instanceof stdClass)) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__(
                        'Input list value under argument \'%s\' and position \'%s\' is not an InputObject type'
                    ),
                    $inputObjectListSubpropertyName,
                    $inputObjectListItemPosition
                )
            );
        }
        return $inputObjectListItemValue;
    }
}
