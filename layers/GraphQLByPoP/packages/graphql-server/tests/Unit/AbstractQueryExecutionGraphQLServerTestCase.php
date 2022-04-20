<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

abstract class AbstractQueryExecutionGraphQLServerTestCase extends AbstractGraphQLServerTestCase
{
    /**
     * @dataProvider graphQLServerExecutionProvider
     */
    public function testGraphQLServerExecution(string $query, array $expectedResponse, array $variables = [], ?string $operationName = null): void
    {
        $this->assertGraphQLQueryExecution($query, $expectedResponse, $variables, $operationName);
    }

    abstract public function graphQLServerExecutionProvider(): array;
}
