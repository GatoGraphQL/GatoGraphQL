<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use PHPUnitForGraphQLAPI\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTest;

class UserLoginWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTest
{
    /**
     * Directory under the fixture files are placed
     */
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-user-login';
    }

    /**
     * @return string[]
     */
    protected static function getGraphQLServerModuleClasses(): array
    {
        return [
            ...parent::getGraphQLServerModuleClasses(),
            ...[
                \PoPWPSchema\Users\Module::class,
                \PoPCMSSchema\UserStateAccessControl\Module::class,
                \PoPCMSSchema\UserStateMutationsWP\Module::class,
                \PoPWPSchema\Pages\Module::class,
                \PoPWPSchema\Posts\Module::class,
                \PoPCMSSchema\CustomPostMutationsWP\Module::class,
                \PoPCMSSchema\PostMutations\Module::class,
                \PoPCMSSchema\CommentMutationsWP\Module::class,
            ]
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getGraphQLServerModuleClassConfiguration(): array
    {
        return [
            ...parent::getGraphQLServerModuleClassConfiguration(),
            ...[
                \PoPCMSSchema\CommentMutations\Module::class => [
                    \PoPCMSSchema\CommentMutations\Environment::MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT => false,
                ],
            ]
        ];
    }
}
