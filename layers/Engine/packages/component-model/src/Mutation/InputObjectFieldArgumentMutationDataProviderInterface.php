<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

interface InputObjectFieldArgumentMutationDataProviderInterface extends FieldArgumentMutationDataProviderInterface
{
    public function getArgumentName(): string;
}
