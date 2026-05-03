<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\QueryResolution;

class NestedMutationsMultipleQueryExecutionPrepareGraphQLForExecutionQueryASTTransformationServiceTest extends AbstractPrepareGraphQLForExecutionQueryASTTransformationServiceTestCase
{
    protected static function isMultipleQueryExecutionEnabled(): bool
    {
        return true;
    }

    protected static function isNestedMutationsEnabled(): bool
    {
        return true;
    }

    /**
     * The parent test asserts the SELF_WRAP output structure (operations
     * wrapped in nested `self` fields). With Multiple Query Execution
     * defaulting to the "Sequential Pass" strategy, no wrapping happens
     * — so this assertion shape no longer applies. Skip rather than
     * delete: the SELF_WRAP path still exists behind the flag, and we
     * may want to revive these tests if it survives long-term.
     */
    public function testPrepareOperationFieldAndFragmentBondsForMultipleQueryExecution(): void
    {
        $this->markTestSkipped(
            'Asserts the SELF_WRAP output structure; default MQE strategy is now SEQUENTIAL_PASS, which skips wrapping.'
        );
    }
}
