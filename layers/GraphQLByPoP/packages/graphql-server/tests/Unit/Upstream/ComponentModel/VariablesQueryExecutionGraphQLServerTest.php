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
        $feedbackItemResolution = new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_5_8_3, ['id']);
        $items = [
            'var-defined' => [
                '
                query Echo($id: ID!) {
                    echo(value: $id)
                }
                ',
                [
                    'data' => [
                        'echo' => "hallo friend!",
                    ],
                ],
                [
                    'id' => "hallo friend!",
                ]
            ],
            'var-not-defined' => [
                '
                query Echo {
                    echo(value: $id)
                }
                ',
                [
                    'errors' => [
                        [
                            'extensions' => [
                                'code' => $feedbackItemResolution->getNamespacedCode(),
                                'specifiedBy' => $feedbackItemResolution->getSpecifiedByURL()
                            ],
                            'locations' => [
                                (new Location(3, 33))->toArray()
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
