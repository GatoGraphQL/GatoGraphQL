<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Unit\Faker;

use GraphQLByPoP\GraphQLServer\Unit\EnabledDisabledFixtureQueryExecutionGraphQLServerTestCaseTrait;
use PHPUnitForGatoGraphQL\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase;
use PoP\Root\Module\ModuleInterface;

abstract class AbstractExposeCoreFunctionalityWPFakerFixtureQueryExecutionGraphQLServerTestCase extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase
{
    use EnabledDisabledFixtureQueryExecutionGraphQLServerTestCaseTrait;

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-expose-core-functionality';
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
                \PoP\ComponentModel\Module::class => [
                    \PoP\ComponentModel\Environment::EXPOSE_CORE_FUNCTIONALITY_GLOBAL_FIELDS => static::isEnabled(),
                ],
            ]
        ];
    }
}
