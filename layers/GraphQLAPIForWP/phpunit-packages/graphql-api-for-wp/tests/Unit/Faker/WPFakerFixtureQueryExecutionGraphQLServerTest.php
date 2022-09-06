<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use PHPUnitForGraphQLAPI\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTest;
use PoP\Root\Module\ModuleInterface;

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
     * @return array<class-string<ModuleInterface>>
     */
    protected static function getGraphQLServerModuleClasses(): array
    {
        return [
            ...parent::getGraphQLServerModuleClasses(),
            ...[
                \PoPCMSSchema\PostCategoriesWP\Module::class,
                \PoPCMSSchema\PostTagsWP\Module::class,
                \PoPWPSchema\Users\Module::class,
                \PoPCMSSchema\UserRolesWP\Module::class,
                \PoPWPSchema\Posts\Module::class,
                \PoPWPSchema\Pages\Module::class,
                \PoPWPSchema\Comments\Module::class,
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
                \PoPCMSSchema\Pages\Module::class => [
                    \PoPCMSSchema\Pages\Environment::ADD_PAGE_TYPE_TO_CUSTOMPOST_UNION_TYPES => true,
                ],
                \PoPCMSSchema\CommentMutations\Module::class => [
                    \PoPCMSSchema\CommentMutations\Environment::MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT => false,
                ],
                \PoPCMSSchema\UserRoles\Module::class => [
                    \PoPCMSSchema\UserRoles\Environment::TREAT_USER_ROLE_AS_SENSITIVE_DATA => false,
                ]
            ]
        ];
    }
}
