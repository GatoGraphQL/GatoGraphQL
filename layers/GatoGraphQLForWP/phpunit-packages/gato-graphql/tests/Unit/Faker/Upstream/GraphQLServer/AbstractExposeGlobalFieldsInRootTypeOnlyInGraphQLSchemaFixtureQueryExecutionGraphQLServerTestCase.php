<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Unit\Faker\Upstream\GraphQLServer;

use GraphQLByPoP\GraphQLServer\Unit\EnabledDisabledFixtureQueryExecutionGraphQLServerTestCaseTrait;
use PHPUnitForGatoGraphQL\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase;
use PoP\Root\Module\ModuleInterface;

abstract class AbstractExposeGlobalFieldsInRootTypeOnlyInGraphQLSchemaFixtureQueryExecutionGraphQLServerTestCase extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase
{
    use EnabledDisabledFixtureQueryExecutionGraphQLServerTestCaseTrait;

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-expose-global-fields-in-root-type-only-in-graphql-schema';
    }

    /**
     * @return array<class-string<ModuleInterface>,array<string,mixed>>
     */
    protected static function getGraphQLServerModuleClassConfiguration(): array
    {
        return [
            ...parent::getGraphQLServerModuleClassConfiguration(),
            ...[
                \GraphQLByPoP\GraphQLServer\Module::class => [
                    \GraphQLByPoP\GraphQLServer\Environment::EXPOSE_GLOBAL_FIELDS_IN_GRAPHQL_SCHEMA => true,
                    \GraphQLByPoP\GraphQLServer\Environment::EXPOSE_GLOBAL_FIELDS_IN_ROOT_TYPE_ONLY_IN_GRAPHQL_SCHEMA => static::isEnabled(),
                ],
            ]
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    protected static function getGraphQLServerModuleClasses(): array
    {
        return [
            ...parent::getGraphQLServerModuleClasses(),
            ...[
                \PoPWPSchema\Pages\Module::class,
            ],
        ];
    }
}
