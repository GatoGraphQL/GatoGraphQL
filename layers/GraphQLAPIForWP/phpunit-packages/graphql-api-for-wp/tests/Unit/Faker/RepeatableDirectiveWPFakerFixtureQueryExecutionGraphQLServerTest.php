<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use PHPUnitForGraphQLAPI\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTest;

class RepeatableDirectiveWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTest
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-repeatable-directive';
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
                \PoPCMSSchema\UserStateMutationsWP\Module::class,
                \PoPCMSSchema\UserRolesAccessControl\Module::class,
                \PoPCMSSchema\UserRolesWP\Module::class,
            ]
        ];
    }
}
