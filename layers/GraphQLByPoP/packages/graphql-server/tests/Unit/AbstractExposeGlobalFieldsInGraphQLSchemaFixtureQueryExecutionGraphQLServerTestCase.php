<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use PoP\Root\Module\ModuleInterface;

abstract class AbstractExposeGlobalFieldsInGraphQLSchemaFixtureQueryExecutionGraphQLServerTestCase extends AbstractEnabledDisabledFixtureQueryExecutionGraphQLServerTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-expose-global-fields-in-graphql-schema';
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
                    \GraphQLByPoP\GraphQLServer\Environment::EXPOSE_GLOBAL_FIELDS_IN_GRAPHQL_SCHEMA => static::isEnabled(),
                ],
            ]
        ];
    }
}
