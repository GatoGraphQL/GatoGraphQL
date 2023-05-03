<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use GraphQLByPoP\GraphQLServer\Unit\EnabledDisabledFixtureQueryExecutionGraphQLServerTestCaseTrait;
use PHPUnitForGraphQLAPI\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase;
use PoP\Root\Module\ModuleInterface;

abstract class AbstractCommentableCustomPostWPFakerFixtureQueryExecutionGraphQLServerTestCase extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase
{
    use EnabledDisabledFixtureQueryExecutionGraphQLServerTestCaseTrait;

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-commentable-custompost';
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    protected static function getGraphQLServerModuleClasses(): array
    {
        $graphQLServerModuleClasses =  [
            ...parent::getGraphQLServerModuleClasses(),
            ...[
                \PoPWPSchema\Pages\Module::class,
                \PoPWPSchema\Posts\Module::class,
            ],
        ];
        if (static::isEnabled()) {
            $graphQLServerModuleClasses[] = \PoPCMSSchema\CommentsWP\Module::class;
        }
        return $graphQLServerModuleClasses;
    }
}
