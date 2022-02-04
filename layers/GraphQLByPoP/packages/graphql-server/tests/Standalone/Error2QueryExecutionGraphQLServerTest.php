<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

class Error2QueryExecutionGraphQLServerTest extends AbstractQueryExecutionGraphQLServerTestCase
{
    public function graphQLServerExecutionProvider(): array
    {
        return [
            'field-is-not-connection' => [
                '
                {
                    id {
                        id
                    }
                }
                ',
                [
                    'data' => [
                        'id' => null,
                    ],
                    'errors' => [
                        [
                            "message" => "Field 'id' is not a connection",
                            "extensions" => [
                                "field" => "id",
                                "type" => "QueryRoot",
                            ],
                            "locations" => [
                                [
                                    "column" => 1,
                                    "line" => 1
                                ]
                            ],
                        ]
                    ]
                ]
            ],
        ];
    }
}
