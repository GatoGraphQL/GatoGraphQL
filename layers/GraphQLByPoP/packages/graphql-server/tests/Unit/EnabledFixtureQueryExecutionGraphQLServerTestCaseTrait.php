<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

trait EnabledFixtureQueryExecutionGraphQLServerTestCaseTrait
{
    protected static function isEnabled(): bool
    {
        return true;
    }
}
