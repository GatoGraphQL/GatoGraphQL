<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

use PoP\Engine\ObjectModels\Root;

class QueryExecutionGraphQLServerTest extends AbstractQueryExecutionGraphQLServerTestCase
{
    public function graphQLServerExecutionProvider(): array
    {
        return [
            'aliased-nested-self' => [
                '
                query {
                    self {
                        first: self {
                            id
                        }
                        second: self {
                            id
                        }
                        third: self {
                            id
                        }
                    }
                }
                ',
                [
                    'data' => [
                        'self' => [
                            'first' => [
                                'id' => Root::ID,
                            ],
                            'second' => [
                                'id' => Root::ID,
                            ],
                            'third' => [
                                'id' => Root::ID,
                            ],
                        ]
                    ]
                ]
            ],
        ];
    }
}
