<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution\MultipleQueryExecution;

class MultipleQueryExecutionDisabledTest extends AbstractMultipleQueryExecutionTest
{
    protected static function enabled(): bool
    {
        return false;
    }
}
