<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class InputObjectFieldArgumentMutationDataProvider extends AbstractInputObjectFieldArgumentMutationDataProvider
{
    public function __construct(
        FieldInterface $field,
        protected string $fieldInputArgumentName,
        array $propertyValues = [],
    ) {
        parent::__construct($field, $propertyValues);
    }

    public function getArgumentName(): string
    {
        return $this->fieldInputArgumentName;
    }
}
