<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

trait DisabledFixtureQueryExecutionGraphQLServerTestCaseTrait
{
    protected static function isEnabled(): bool
    {
        return false;
    }
}
