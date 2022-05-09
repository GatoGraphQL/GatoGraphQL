<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

trait DisabledFixtureQueryExecutionGraphQLServerTestTrait
{
    protected static function isEnabled(): bool
    {
        return false;
    }
}
