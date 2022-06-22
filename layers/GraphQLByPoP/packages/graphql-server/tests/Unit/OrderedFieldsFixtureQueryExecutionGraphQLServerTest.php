<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

class OrderedFieldsFixtureQueryExecutionGraphQLServerTest extends AbstractFixtureQueryExecutionGraphQLServerTestCase
{
    /**
     * Directory under the fixture files are placed
     */
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-ordered-fields';
    }

    /**
     * Override, to also test the order of the fields
     */
    protected function doAssertFixtureGraphQLQueryExecution(string $expectedResponseFile, string $actualResponseContent): void
    {
        $this->assertSame(
            json_encode(json_decode(file_get_contents($expectedResponseFile)), JSON_PRETTY_PRINT),
            json_encode(json_decode($actualResponseContent), JSON_PRETTY_PRINT)
        );
    }
}
