<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractFieldArgumentMutationDataProvider implements FieldArgumentMutationDataProviderInterface
{
    public function __construct(
        protected FieldInterface $field
    ) {
    }

    public function getField(): FieldInterface
    {
        return $this->field;
    }
}
