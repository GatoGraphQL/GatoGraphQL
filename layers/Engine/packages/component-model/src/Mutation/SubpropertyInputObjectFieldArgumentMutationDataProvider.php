<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class SubpropertyInputObjectFieldArgumentMutationDataProvider extends AbstractSubpropertyInputObjectFieldArgumentMutationDataProvider
{
    public function __construct(
        FieldInterface $field,
        protected string $fieldInputArgumentName,
        protected string $subpropertyName,
        array $propertyValues = [],
    ) {
        parent::__construct($field, $propertyValues);
    }

    public function getArgumentName(): string
    {
        return $this->fieldInputArgumentName;
    }

    protected function getSubpropertyName(): string
    {
        return $this->subpropertyName;
    }
}
