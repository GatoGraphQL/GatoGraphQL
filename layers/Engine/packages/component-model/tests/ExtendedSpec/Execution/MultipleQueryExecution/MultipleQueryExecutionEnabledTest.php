<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ExtendedSpec\Execution\MultipleQueryExecution;

class MultipleQueryExecutionEnabledTest extends AbstractMultipleQueryExecutionTestCase
{
    protected static function enabled(): bool
    {
        return true;
    }
}
