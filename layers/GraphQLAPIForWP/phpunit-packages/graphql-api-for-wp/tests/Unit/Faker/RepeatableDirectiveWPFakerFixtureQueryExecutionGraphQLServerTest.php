<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use PHPUnitForGraphQLAPI\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTest;
use PoP\Root\Module\ModuleInterface;

class RepeatableDirectiveWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTest
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-repeatable-directive';
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
                \PoPCMSSchema\UserRolesAccessControl\Module::class,
                \PoPCMSSchema\UserRolesWP\Module::class,
            ]
        ];
    }
}
