<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

class GraphQLServerTest extends AbstractGraphQLServerTestCase
{
    public function graphQLServerExecutionProvider(): array
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
