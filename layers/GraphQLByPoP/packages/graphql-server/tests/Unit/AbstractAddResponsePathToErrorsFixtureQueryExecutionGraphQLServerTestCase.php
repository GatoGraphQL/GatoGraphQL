<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use PoP\Root\Module\ModuleInterface;

abstract class AbstractAddResponsePathToErrorsFixtureQueryExecutionGraphQLServerTestCase extends AbstractEnabledDisabledFixtureQueryExecutionGraphQLServerTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-add-response-path-to-errors';
    }

    /**
     * @return array<class-string<ModuleInterface>,array<string,mixed>>
     */
    protected static function getGraphQLServerModuleClassConfiguration(): array
    {
        return [
            ...parent::getGraphQLServerModuleClassConfiguration(),
            ...[
                \PoP\ComponentModel\Module::class => [
                    \PoP\ComponentModel\Environment::ADD_RESPONSE_PATH_TO_ERRORS => static::isEnabled(),
                ],
            ]
        ];
    }
}
