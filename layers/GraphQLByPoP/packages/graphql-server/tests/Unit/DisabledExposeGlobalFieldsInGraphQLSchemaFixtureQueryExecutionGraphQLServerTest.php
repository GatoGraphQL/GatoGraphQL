<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

class DisabledExposeGlobalFieldsInGraphQLSchemaFixtureQueryExecutionGraphQLServerTest extends AbstractExposeGlobalFieldsInGraphQLSchemaFixtureQueryExecutionGraphQLServerTest
{
    protected static function isEnabled(): bool
    {
        return false;
    }
}
