<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

trait EnabledFixtureQueryExecutionGraphQLServerTestTrait
{
    protected static function isEnabled(): bool
    {
        return true;
    }
}
