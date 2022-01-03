<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Execution;

class MultipleQueryExecutionDisabledTest extends AbstractMultipleQueryExecutionTest
{
    protected function enabled(): bool
    {
        return false;
    }
}
