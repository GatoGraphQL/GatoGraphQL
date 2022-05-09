<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

class DisabledSkipDangerouslyDynamicScalarTypeFixtureQueryExecutionGraphQLServerTest extends AbstractSkipDangerouslyDynamicScalarTypeFixtureQueryExecutionGraphQLServerTest
{
    protected static function isEnabled(): bool
    {
        return false;
    }
}
