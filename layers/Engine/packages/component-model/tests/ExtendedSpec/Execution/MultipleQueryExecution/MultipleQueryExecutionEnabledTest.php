<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ExtendedSpec\Execution\MultipleQueryExecution;

class MultipleQueryExecutionEnabledTest extends AbstractMultipleQueryExecutionTest
{
    protected static function enabled(): bool
    {
        return true;
    }
}
