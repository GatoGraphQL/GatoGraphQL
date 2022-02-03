<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

abstract class AbstractQueryExecutionGraphQLServerTestCase extends AbstractGraphQLServerTestCase
{
    /**
     * @dataProvider graphQLServerExecutionProvider
     */
    public function testGraphQLServerExecution(string $query, array $expectedResponse): void
    {
        $this->assertGraphQLQueryExecution($query, $expectedResponse);
    }

    abstract public function graphQLServerExecutionProvider(): array;
}
