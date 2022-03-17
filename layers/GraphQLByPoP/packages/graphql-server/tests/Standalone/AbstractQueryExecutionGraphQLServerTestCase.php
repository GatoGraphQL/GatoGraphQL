<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

abstract class AbstractQueryExecutionGraphQLServerTestCase extends AbstractGraphQLServerTestCase
{
    /**
     * @dataProvider graphQLServerExecutionProvider
     */
    public function testGraphQLServerExecution(string $query, array $expectedResponse, array $variables = []): void
    {
        $this->assertGraphQLQueryExecution($query, $expectedResponse, $variables);
    }

    abstract public function graphQLServerExecutionProvider(): array;
}
