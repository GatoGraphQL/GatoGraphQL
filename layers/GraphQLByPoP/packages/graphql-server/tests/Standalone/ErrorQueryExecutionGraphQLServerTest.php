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
                            "extensions" => [
                                "code" => "gql-6.1.c",
                                "specifiedBy" => "https://spec.graphql.org/draft/#sec-Executing-Requests"
                            ],
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
