<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use GraphQLByPoP\GraphQLServer\Unit\EnabledDisabledFixtureQueryExecutionGraphQLServerTestCaseTrait;
use PHPUnitForGraphQLAPI\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTest;
use PoP\Root\Module\ModuleInterface;

abstract class AbstractEnabledDisabledUsePayloadableCommentMutationsWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTest
{
    use EnabledDisabledFixtureQueryExecutionGraphQLServerTestCaseTrait;
    
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-use-payloadable-comment-mutations-or-not';
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
                \PoPCMSSchema\UserStateMutationsWP\Module::class,
                \PoPWPSchema\Posts\Module::class,
                \PoPCMSSchema\CustomPostMutationsWP\Module::class,
                \PoPCMSSchema\PostMutations\Module::class,
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
                    \PoPCMSSchema\CommentMutations\Environment::MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT => true,
                    \PoPCMSSchema\CommentMutations\Environment::USE_PAYLOADABLE_COMMENT_MUTATIONS => static::isEnabled(),
                ],
            ]
        ];
    }
}
