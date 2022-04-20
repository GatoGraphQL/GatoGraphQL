<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit;

use PHPUnitForGraphQLAPI\WPFakerSchema\Standalone\AbstractWPFakerFixtureQueryExecutionGraphQLServerTest;

class WPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTest
{
    /**
     * Directory under the fixture files are placed
     */
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture';
    }

    /**
     * @return string[]
     */
    protected static function getGraphQLServerComponentClasses(): array
    {
        return [
            ...parent::getGraphQLServerComponentClasses(),
            ...[
                \PoPCMSSchema\PostCategoriesWP\Component::class,
                \PoPCMSSchema\PostTagsWP\Component::class,
                \PoPWPSchema\Users\Component::class,
                \PoPWPSchema\Posts\Component::class,
                \PoPWPSchema\Pages\Component::class,
                \PoPWPSchema\Comments\Component::class,
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
                \PoPCMSSchema\Pages\Component::class => [
                    \PoPCMSSchema\Pages\Environment::ADD_PAGE_TYPE_TO_CUSTOMPOST_UNION_TYPES => true,
                ],
                \PoPCMSSchema\CommentMutations\Component::class => [
                    \PoPCMSSchema\CommentMutations\Environment::MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT => false,
                ],
            ]
        ];
    }
}
