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
                \GraphQLAPI\WPFakerSchema\Component::class,
                \PoPCMSSchema\PostCategoriesWP\Component::class,
                \PoPCMSSchema\PostTagsWP\Component::class,
                \PoPWPSchema\Pages\Component::class,
                \PoPCMSSchema\CommentMutationsWP\Component::class,
                \PoPCMSSchema\UserStateMutationsWP\Component::class,
            ]
        ];
    }
}
