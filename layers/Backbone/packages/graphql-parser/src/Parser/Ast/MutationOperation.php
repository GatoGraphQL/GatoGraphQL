<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

class MutationOperation extends AbstractOperation
{
    public function getOperationType(): string
    {
        return OperationTypes::MUTATION;
    }
}
