<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Standalone\Upstream\ComponentModel;

use PHPUnitForGraphQLAPI\WPFakerSchema\Standalone\AbstractWPFakerFixtureQueryExecutionGraphQLServerTest;

class NestedMutationWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTest
{
    /**
     * Directory under the fixture files are placed
     */
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-nested-mutation';
    }

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
                \PoPWPSchema\Comments\Component::class,
                \PoPCMSSchema\CommentMutationsWP\Component::class,
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
