<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

class ErrorQueryExecutionGraphQLServerTest extends AbstractQueryExecutionGraphQLServerTestCase
{
    public function graphQLServerExecutionProvider(): array
    {
        return [
            'empty' => [
                '
                ',
                [
                    'errors' => [
                        [
                            "message" => "The query has not been provided",
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
