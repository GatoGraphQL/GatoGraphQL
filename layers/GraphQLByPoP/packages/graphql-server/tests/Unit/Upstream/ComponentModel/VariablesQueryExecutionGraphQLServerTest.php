<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit\Upstream\ComponentModel;

use GraphQLByPoP\GraphQLServer\Unit\AbstractGraphQLServerTestCase;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Feedback\FeedbackItemResolution;

class VariablesQueryExecutionGraphQLServerTest extends AbstractGraphQLServerTestCase
{
    public function testQueries(): void
    {
        $feedbackItemResolution = new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_5_8_3, ['include']);
        $items = [
            'var-defined' => [
                '
                query Echo($include: Boolean!, $skip: Boolean!) {
                    id @include(if: $include)
                    again: id @skip(if: $skip)
                }
                ',
                [
                    'data' => [
                        'id' => "root",
                    ],
                ],
                [
                    'include' => true,
                    'skip' => true,
                ]
            ],
            'var-not-defined' => [
                '
                query Echo {
                    id @include(if: $include)
                }
                ',
                [
                    'errors' => [
                        [
                            'extensions' => [
                                'code' => $feedbackItemResolution->getNamespacedCode(),
                                'path' => [
                                    '$include',
                                    '(if: $include)',
                                    '@include(if: $include)',
                                    'id @include(if: $include)',
                                    'query Echo { ... }',
                                ],
                                'specifiedBy' => $feedbackItemResolution->getSpecifiedByURL()
                            ],
                            'locations' => [
                                (new Location(3, 37))->toArray()
                            ],
                            'message' => $feedbackItemResolution->getMessage(),
                        ],
                    ],
                ],
            ],
        ];
        foreach ($items as $item) {
            $item[2] ??= [];
            [$query, $expectedResponse, $variables] = $item;
            $this->assertGraphQLQueryExecution($query, $expectedResponse, $variables);
        }
    }
}
