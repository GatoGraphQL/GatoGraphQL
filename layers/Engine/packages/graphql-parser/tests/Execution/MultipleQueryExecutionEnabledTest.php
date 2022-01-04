<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Execution;

class MultipleQueryExecutionEnabledTest extends AbstractMultipleQueryExecutionTest
{
    protected static function enabled(): bool
    {
        return true;
    }
}
