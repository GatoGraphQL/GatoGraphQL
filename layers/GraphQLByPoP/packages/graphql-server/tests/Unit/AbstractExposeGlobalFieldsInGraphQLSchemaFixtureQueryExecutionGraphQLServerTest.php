<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

abstract class AbstractExposeGlobalFieldsInGraphQLSchemaFixtureQueryExecutionGraphQLServerTest extends AbstractEnabledDisabledFixtureQueryExecutionGraphQLServerTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-expose-global-fields-in-graphql-schema';
    }

    /**
     * @return array<string,mixed>
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
