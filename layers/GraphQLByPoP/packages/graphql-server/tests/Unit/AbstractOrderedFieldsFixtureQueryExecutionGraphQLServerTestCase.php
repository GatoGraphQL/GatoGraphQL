<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use RuntimeException;

abstract class AbstractOrderedFieldsFixtureQueryExecutionGraphQLServerTestCase extends AbstractFixtureQueryExecutionGraphQLServerTestCase
{
    /**
     * Override assertion, to also test the order of the fields
     */
    protected function doAssertFixtureGraphQLQueryExecution(string $expectedResponseFile, string $actualResponseContent): void
    {
        $fileContents = file_get_contents($expectedResponseFile);
        if ($fileContents === false) {
            throw new RuntimeException(sprintf('Cannot read the contents of file \'%s\'', $expectedResponseFile));
        }
        $this->assertSame(
            json_encode(json_decode($fileContents), JSON_PRETTY_PRINT),
            json_encode(json_decode($actualResponseContent), JSON_PRETTY_PRINT)
        );
    }
}
