<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use PHPUnit\Framework\Attributes\DataProvider;

abstract class AbstractQueryExecutionGraphQLServerTestCase extends AbstractGraphQLServerTestCase
{
    /**
     * @param mixed[] $expectedResponse
     * @param array<string,mixed> $variables
     */
    #[DataProvider('graphQLServerExecutionProvider')]
    public function testGraphQLServerExecution(string $query, array $expectedResponse, array $variables = [], ?string $operationName = null): void
    {
        $this->assertGraphQLQueryExecution($query, $expectedResponse, $variables, $operationName);
    }

    /**
     * @return mixed[]
     */
    abstract public static function graphQLServerExecutionProvider(): array;
}
