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
                            "location" => [
                                "column" => 1,
                                "line" => 1
                            ],
                            "message" => "The query has not been provided"
                        ]
                    ]
                ]
            ],
        ];
    }
}
