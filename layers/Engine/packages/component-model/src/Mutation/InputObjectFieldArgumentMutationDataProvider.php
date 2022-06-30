<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class InputObjectFieldArgumentMutationDataProvider extends AbstractInputObjectFieldArgumentMutationDataProvider
{
    public function __construct(
        FieldInterface $field,
        protected string $fieldInputArgumentName
    ) {
        parent::__construct($field);
    }

    protected function getArgumentName(): string
    {
        return $this->fieldInputArgumentName;
    }
}
