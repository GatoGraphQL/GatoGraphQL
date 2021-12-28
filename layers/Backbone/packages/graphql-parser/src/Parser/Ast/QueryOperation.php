<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

class QueryOperation extends AbstractOperation
{
    public function getOperationType(): string
    {
        return OperationTypes::QUERY;
    }
}
