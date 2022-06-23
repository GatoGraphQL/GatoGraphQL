<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery\Schema;

use GraphQLByPoP\GraphQLQuery\Schema\QuerySymbols;
use PoP\Root\Services\BasicServiceTrait;

class GraphQLQueryConvertor implements GraphQLQueryConvertorInterface
{
    use BasicServiceTrait;

    /**
     * Indicates if the variable must be dealt with as an expression: if its name starts with "_"
     */
    public function treatVariableAsExpression(string $variableName): bool
    {
        return
            substr($variableName, 0, strlen(QuerySymbols::VARIABLE_AS_EXPRESSION_NAME_PREFIX)) == QuerySymbols::VARIABLE_AS_EXPRESSION_NAME_PREFIX
            /**
             * Switched from "%{...}%" to "$__..."
             * @todo Convert expressions from "$__" to "$"
             */
            && !str_starts_with($variableName, '__');
    }
}
