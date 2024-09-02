<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Unit\Faker;

use PHPUnitForGatoGraphQL\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase;
use PoP\Root\Module\ModuleInterface;

class WPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase
{
    /**
     * Directory under the fixture files are placed
     */
    protected static function getFixtureFolder(): string
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
                \PHPUnitForGatoGraphQL\DummySchema\Module::class,
            ]
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>,array<string,mixed>>
     */
    protected static function getGraphQLServerModuleClassConfiguration(): array
    {
        return [
            ...parent::getGraphQLServerModuleClassConfiguration(),
            ...[
                \PoPCMSSchema\CommentMutations\Module::class => [
                    \PoPCMSSchema\CommentMutations\Environment::MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT => false,
                ],
                \PoPCMSSchema\UserRoles\Module::class => [
                    \PoPCMSSchema\UserRoles\Environment::TREAT_USER_ROLE_AS_SENSITIVE_DATA => false,
                ],
                \PoPCMSSchema\Users\Module::class => [
                    \PoPCMSSchema\Users\Environment::TREAT_USER_EMAIL_AS_SENSITIVE_DATA => false,
                ],
                \PoP\ComponentModel\Module::class => [
                    \PoP\ComponentModel\Environment::EXPOSE_CORE_FUNCTIONALITY_GLOBAL_FIELDS => true,
                ],
                \PoPCMSSchema\CustomPosts\Module::class => [
                    \PoPCMSSchema\CustomPosts\Environment::TREAT_CUSTOMPOST_STATUS_AS_SENSITIVE_DATA => false,
                ],
                \PoPCMSSchema\Categories\Module::class => [
                    \PoPCMSSchema\Categories\Environment::QUERYABLE_CATEGORY_TAXONOMIES => ['category'],
                ],
                \PoPCMSSchema\Tags\Module::class => [
                    \PoPCMSSchema\Tags\Environment::QUERYABLE_TAG_TAXONOMIES => ['post_tag'],
                ],
            ]
        ];
    }
}
