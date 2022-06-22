<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery\Schema;

interface GraphQLQueryConvertorInterface
{
    /**
     * Indicates if the variable must be dealt with as an expression: if its name starts with "_"
     */
    public function treatVariableAsExpression(string $variableName): bool;
}
