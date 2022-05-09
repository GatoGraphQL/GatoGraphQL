<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

class EnabledExposeGlobalFieldsInGraphQLSchemaFixtureQueryExecutionGraphQLServerTest extends AbstractExposeGlobalFieldsInGraphQLSchemaFixtureQueryExecutionGraphQLServerTest
{
    protected static function isEnabled(): bool
    {
        return true;
    }
}
