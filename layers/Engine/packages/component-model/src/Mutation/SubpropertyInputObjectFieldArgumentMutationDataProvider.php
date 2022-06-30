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
    ) {
        parent::__construct($field);
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
