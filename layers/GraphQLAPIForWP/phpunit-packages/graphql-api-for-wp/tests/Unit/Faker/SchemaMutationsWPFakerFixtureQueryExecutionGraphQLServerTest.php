<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use PHPUnitForGraphQLAPI\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTest;
use PoP\Root\Module\ModuleInterface;

use function Brain\Monkey\Functions\expect;

class SchemaMutationsWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTest
{
    /**
     * Directory under the fixture files are placed
     */
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-mutations';
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    protected static function getGraphQLServerModuleClasses(): array
    {
        return [
            ...parent::getGraphQLServerModuleClasses(),
            ...[
                \PoPWPSchema\Users\Module::class,
                \PoPWPSchema\Posts\Module::class,
                \PoPWPSchema\Pages\Module::class,
                \PoPWPSchema\Comments\Module::class,
                \PoPCMSSchema\CustomPostMediaMutationsWP\Module::class,
                \PoPCMSSchema\CommentMutationsWP\Module::class,
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
            ]
        ];
    }

    protected static function mockFunctions(): void
    {
        parent::mockFunctions();

        /**
         * Override function to not support featured image for pages
         */
        expect('post_type_supports')
            ->andReturnUsing(
                function (string $post_type, string $feature): bool
                {
                    if (in_array($feature, ['comments', 'thumbnail'])) {
                        return $post_type === 'post';
                    }
                    return true;
                }
            );
    }
}
