<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

class InputFieldArgumentMutationDataProvider extends AbstractInputObjectFieldArgumentMutationDataProvider
{
    protected function getArgumentName(): string
    {
        return 'input';
    }
}
