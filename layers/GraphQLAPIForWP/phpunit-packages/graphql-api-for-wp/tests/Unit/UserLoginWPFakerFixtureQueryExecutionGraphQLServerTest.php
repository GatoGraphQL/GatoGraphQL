<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit;

use PHPUnitForGraphQLAPI\WPFakerSchema\Standalone\AbstractWPFakerFixtureQueryExecutionGraphQLServerTest;

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
    protected static function getGraphQLServerComponentClasses(): array
    {
        return [
            ...parent::getGraphQLServerComponentClasses(),
            ...[
                \PoPWPSchema\Users\Component::class,
                \PoPCMSSchema\UserStateMutationsWP\Component::class,
            ]
        ];
    }
}
