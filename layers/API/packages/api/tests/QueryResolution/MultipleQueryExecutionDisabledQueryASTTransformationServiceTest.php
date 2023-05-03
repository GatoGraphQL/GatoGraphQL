<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

class MultipleQueryExecutionDisabledQueryASTTransformationServiceTest extends AbstractMultipleQueryExecutionQueryASTTransformationServiceTestCase
{
    protected static function enabled(): bool
    {
        return false;
    }
}
