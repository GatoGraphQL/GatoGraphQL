<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

class EnabledSkipDangerouslyDynamicScalarTypeFixtureQueryExecutionGraphQLServerTest extends AbstractSkipDangerouslyDynamicScalarTypeFixtureQueryExecutionGraphQLServerTest
{
    protected static function isEnabled(): bool
    {
        return true;
    }
}
