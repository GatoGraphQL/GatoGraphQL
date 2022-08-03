<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
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

    protected function getInputObjectValue(): stdClass
    {
        return $this->getResolvedFieldArgs()[$this->getInputObjectSubpropertyName()];
    }
}
