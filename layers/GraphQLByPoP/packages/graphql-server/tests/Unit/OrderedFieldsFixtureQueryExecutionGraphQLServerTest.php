<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

class OrderedFieldsFixtureQueryExecutionGraphQLServerTest extends AbstractOrderedFieldsFixtureQueryExecutionGraphQLServerTest
{
    /**
     * Directory under the fixture files are placed
     */
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-ordered-fields';
    }
}
