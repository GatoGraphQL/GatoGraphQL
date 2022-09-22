<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\QueryResolution;

class MultipleQueryExecutionPrepareGraphQLForExecutionQueryASTTransformationServiceTest extends AbstractPrepareGraphQLForExecutionQueryASTTransformationServiceTest
{
    protected static function isMultipleQueryExecutionEnabled(): bool
    {
        return true;
    }

    protected static function isNestedMutationsEnabled(): bool
    {
        return false;
    }
}
