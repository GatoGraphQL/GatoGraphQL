<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit\Upstream\ComponentModel;

use GraphQLByPoP\GraphQLServer\Unit\AbstractGraphQLServerTestCase;

/**
 * Test: Handle error from passing WP_Post as GraphQL variable
 */
class InputValueCoercionQueryExecutionGraphQLServerTest extends AbstractGraphQLServerTestCase
{
    public function testQueries(): void
    {
        $items = [
            'var-object-not-supported' => [
                '
                query Echo($include: Boolean!) {
                    id @include(if: $include)
                }
                ',
                [
                    "errors" => [
                        [
                            "message" => "Argument 'if' of type 'Boolean' cannot be an object",
                            "locations" => [
                                [
                                    "line" => 3,
                                    "column" => 37
                                ]
                            ],
                            "extensions" => [
                                "path" => [
                                    "\$include",
                                    "(if: \$include)",
                                    "@include(if: \$include)",
                                    "id @include(if: \$include)",
                                    "query Echo(\$include: Boolean!) { ... }"
                                ],
                                "type" => "QueryRoot",
                                "field" => "id @include(if: \$include)",
                                "code" => "gql@5.6.1[20]",
                                "specifiedBy" => "https://spec.graphql.org/draft/#sec-Values-of-Correct-Type"
                            ]
                        ]
                    ],
                    "data" => [
                        "id" => null
                    ]
                ],
                // Pass any non-stdClass object here
                [
                    'include' => static::getGraphQLServer(),
                ]
            ],
        ];
        foreach ($items as $item) {
            [$query, $expectedResponse, $variables] = $item;
            $this->assertGraphQLQueryExecution($query, $expectedResponse, $variables);
        }
    }
}
