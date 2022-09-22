<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use PHPUnitForGraphQLAPI\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTest;
use PoP\Root\Module\ModuleInterface;

class NestedMutationsWithMultipleQueryExecutionWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTest
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-nested-mutations-and-multiple-query-execution';
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
                \PoPSchema\ConvertCaseDirectives\Module::class,
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
                \PoP\GraphQLParser\Module::class => [
                    \PoP\GraphQLParser\Environment::ENABLE_MULTIPLE_QUERY_EXECUTION => true,
                ],                
            ]
        ];
    }
}
