<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\Standalone;

use GraphQLAPI\WPFakerSchema\Standalone\GraphQLServerTestCaseTrait;
use GraphQLByPoP\GraphQLServer\Standalone\AbstractFixtureQueryExecutionGraphQLServerTestCase;

abstract class AbstractWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractFixtureQueryExecutionGraphQLServerTestCase
{
    use GraphQLServerTestCaseTrait;

    /**
     * @return string[]
     */
    protected static function getGraphQLServerComponentClasses(): array
    {
        return [
            ...parent::getGraphQLServerComponentClasses(),
            ...[
                \PoPWPSchema\Users\Component::class,
                \PoPWPSchema\Posts\Component::class,
                \GraphQLAPI\WPFakerSchema\Component::class,
            ]
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getGraphQLServerComponentClassConfiguration(): array
    {
        return [
            ...parent::getGraphQLServerComponentClassConfiguration(),
            ...[
                \GraphQLAPI\WPFakerSchema\Component::class => [
                    \GraphQLAPI\WPFakerSchema\Environment::USE_FIXED_DATASET => true,
                ],
            ]
        ];
    }
}
