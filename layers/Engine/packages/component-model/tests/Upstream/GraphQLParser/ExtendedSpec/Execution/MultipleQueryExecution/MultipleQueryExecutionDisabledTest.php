<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\MultipleQueryExecution;

class MultipleQueryExecutionDisabledTest extends AbstractMultipleQueryExecutionTest
{
    protected static function enabled(): bool
    {
        return false;
    }
}
