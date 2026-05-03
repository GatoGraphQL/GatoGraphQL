<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

/**
 * Verifies that with Multiple Query Execution enabled in "Sequential Pass"
 * mode, `prepareOperationFieldAndFragmentBondsForMultipleQueryExecution`
 * returns each operation's fields un-wrapped — the engine handles ordering
 * by draining one operation at a time, so the nested `self` fields are
 * unnecessary.
 */
class MultipleQueryExecutionSequentialPassQueryASTTransformationServiceTest extends AbstractMultipleQueryExecutionQueryASTTransformationServiceTestCase
{
    protected static function enabled(): bool
    {
        return true;
    }

    protected static function sequential(): bool
    {
        return true;
    }
}
