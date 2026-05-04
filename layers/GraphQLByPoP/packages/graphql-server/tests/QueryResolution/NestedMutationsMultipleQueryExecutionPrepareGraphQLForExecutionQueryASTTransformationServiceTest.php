<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\QueryResolution;

class NestedMutationsMultipleQueryExecutionPrepareGraphQLForExecutionQueryASTTransformationServiceTest extends AbstractPrepareGraphQLForExecutionQueryASTTransformationServiceTestCase
{
    protected static function isNestedMutationsEnabled(): bool
    {
        return true;
    }
}
