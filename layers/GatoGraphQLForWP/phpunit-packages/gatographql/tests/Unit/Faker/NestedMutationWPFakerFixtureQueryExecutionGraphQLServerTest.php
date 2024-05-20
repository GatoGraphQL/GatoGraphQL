<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Unit\Faker;

use PHPUnitForGatoGraphQL\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase;
use PoP\Root\Module\ModuleInterface;

use function Brain\Monkey\Functions\expect;

class NestedMutationWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase
{
    /**
     * Directory under the fixture files are placed
     */
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-nested-mutation';
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
                \PoPCMSSchema\CommentMutationsWP\Module::class,
                \PoPCMSSchema\CustomPostMutationsWP\Module::class,
                \PoPCMSSchema\PostMutations\Module::class,
                \PoPCMSSchema\PageMutationsWP\Module::class,
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
                \GraphQLByPoP\GraphQLServer\Module::class => [
                    \GraphQLByPoP\GraphQLServer\Environment::ENABLE_NESTED_MUTATIONS => true,
                ],
            ]
        ];
    }

    protected static function mockFunctions(): void
    {
        parent::mockFunctions();

        /**
         * Override function to also support comments for pages
         */
        expect('post_type_supports')
            ->with('post_type', 'page')
            ->with('feature', 'comments')
            ->andReturn(true);
    }
}
