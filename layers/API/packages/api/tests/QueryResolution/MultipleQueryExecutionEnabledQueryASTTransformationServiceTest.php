<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

class MultipleQueryExecutionEnabledQueryASTTransformationServiceTest extends AbstractMultipleQueryExecutionQueryASTTransformationServiceTestCase
{
    protected static function enabled(): bool
    {
        return true;
    }
}
