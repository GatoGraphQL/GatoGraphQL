<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use PoP\Root\Module\ModuleInterface;

abstract class AbstractConvertInputValueFromSingleToListFixtureQueryExecutionGraphQLServerTestCase extends AbstractEnabledDisabledFixtureQueryExecutionGraphQLServerTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-convert-input-value-from-single-to-list';
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
                    \PoP\ComponentModel\Environment::CONVERT_INPUT_VALUE_FROM_SINGLE_TO_LIST => static::isEnabled(),
                ],
            ]
        ];
    }
}
