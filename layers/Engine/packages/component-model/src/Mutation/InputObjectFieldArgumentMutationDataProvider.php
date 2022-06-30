<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

class InputObjectFieldArgumentMutationDataProvider extends AbstractInputObjectFieldArgumentMutationDataProvider
{
    protected function getArgumentName(): string
    {
        return 'input';
    }
}
