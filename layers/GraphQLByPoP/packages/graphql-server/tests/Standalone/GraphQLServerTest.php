<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

use PoP\Engine\ObjectModels\Root;

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
                        'id' => Root::ID,
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
                            'id' => Root::ID,
                        ]
                    ]
                ]
            ],
        ];
    }
}
