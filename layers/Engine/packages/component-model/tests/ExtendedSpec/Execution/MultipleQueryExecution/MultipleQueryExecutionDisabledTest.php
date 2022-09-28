<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ExtendedSpec\Execution\MultipleQueryExecution;

class MultipleQueryExecutionDisabledTest extends AbstractMultipleQueryExecutionTest
{
    protected static function enabled(): bool
    {
        return false;
    }
}
