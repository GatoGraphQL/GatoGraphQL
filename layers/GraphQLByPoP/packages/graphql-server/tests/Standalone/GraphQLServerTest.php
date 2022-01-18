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
                '
                query {
                    id
                }
                ',
                [
                    'data' => [
                        'id' => 'root',
                    ]
                ]
            ],
            'self' => [
                '
                query {
                    self {
                        id
                    }
                }
                ',
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
