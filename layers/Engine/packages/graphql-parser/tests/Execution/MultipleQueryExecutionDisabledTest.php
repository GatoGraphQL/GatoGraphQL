<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Execution;

class MultipleQueryExecutionDisabledTest extends AbstractMultipleQueryExecutionTest
{
    protected static function enabled(): bool
    {
        return false;
    }
}
