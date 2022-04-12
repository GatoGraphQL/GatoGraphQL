<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\Standalone\Upstream\ComponentModel;

use GraphQLAPI\WPFakerSchema\Standalone\AbstractWPFakerFixtureQueryExecutionGraphQLServerTest;

class WPFakerFixture2QueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTest
{
    /**
     * Directory under the fixture files are placed
     */
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/Fixture2';
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getGraphQLServerComponentClassConfiguration(): array
    {
        return [
            ...parent::getGraphQLServerComponentClassConfiguration(),
            ...[
                \PoPCMSSchema\CommentMutations\Component::class => [
                    \PoPCMSSchema\CommentMutations\Environment::MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT => false,
                ],
                \GraphQLByPoP\GraphQLServer\Component::class => [
                    \GraphQLByPoP\GraphQLServer\Environment::ENABLE_NESTED_MUTATIONS => true,
                ],
            ]
        ];
    }
}
