<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\QueryResolution;

class PrepareGraphQLForExecutionQueryASTTransformationServiceTest extends AbstractPrepareGraphQLForExecutionQueryASTTransformationServiceTestCase
{
    protected static function isMultipleQueryExecutionEnabled(): bool
    {
        return false;
    }

    protected static function isNestedMutationsEnabled(): bool
    {
        return false;
    }
}
