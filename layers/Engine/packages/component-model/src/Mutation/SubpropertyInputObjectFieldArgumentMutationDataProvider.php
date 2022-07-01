<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use stdClass;

class SubpropertyInputObjectFieldArgumentMutationDataProvider extends InputObjectFieldArgumentMutationDataProvider implements SubpropertyInputObjectFieldArgumentMutationDataProviderInterface
{
    public function __construct(
        FieldInterface $field,
        string $fieldInputArgumentName,
        protected string $subpropertyName,
        array $propertyValues = [],
    ) {
        parent::__construct($field, $fieldInputArgumentName, $propertyValues);
    }

    public function getSubpropertyName(): string
    {
        return $this->subpropertyName;
    }

    protected function getInputObjectValue(): stdClass
    {
        $inputObjectValue = parent::getInputObjectValue();
        $subpropertyName = $this->getSubpropertyName();
        return $inputObjectValue->$subpropertyName;
    }
}
