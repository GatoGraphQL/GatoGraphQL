<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use PoP\Root\Module\ModuleInterface;

abstract class AbstractPropagateNullToNearestNullableParentFixtureQueryExecutionGraphQLServerTestCase extends AbstractEnabledDisabledFixtureQueryExecutionGraphQLServerTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-propagate-null-to-nearest-nullable-parent';
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
                    \PoP\ComponentModel\Environment::PROPAGATE_NULL_TO_NEAREST_NULLABLE_PARENT => static::isEnabled(),
                ],
            ]
        ];
    }
}
