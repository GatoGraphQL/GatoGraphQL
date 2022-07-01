<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface InputObjectFieldArgumentMutationDataProviderInterface extends MutationDataProviderInterface
{
    public function getField(): FieldInterface;
    public function getArgumentName(): string;
}
