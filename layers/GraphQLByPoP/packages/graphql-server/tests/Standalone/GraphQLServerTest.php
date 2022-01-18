<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

class GraphQLServerTest extends AbstractGraphQLServerTestCase
{
    /**
     * @dataProvider queryProvider
     */
    public function testGraphQLServerExecution(string $query, array $response): void
    {
        $this->expectOutputString(json_encode($response));
        self::getGraphQLServer()->execute($query);
    }

    public function queryProvider(): array
    {
        return [
            'id' => [
                <<<GRAPHQL
                query {
                    id
                }
                GRAPHQL,
                [
                    'data' => [
                        'id' => 'root',
                    ]
                ]
            ],
            'self' => [
                <<<GRAPHQL
                query {
                    self {
                        id
                    }
                }
                GRAPHQL,
                [
                    'data' => [
                        'self' => [
                            'id' => 'root',
                        ]
                    ]
                ]
            ],
        ];
    }
}
