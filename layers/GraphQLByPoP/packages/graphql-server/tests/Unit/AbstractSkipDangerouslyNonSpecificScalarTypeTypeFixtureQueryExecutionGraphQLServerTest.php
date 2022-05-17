<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

abstract class AbstractSkipDangerouslyNonSpecificScalarTypeTypeFixtureQueryExecutionGraphQLServerTest extends AbstractEnabledDisabledFixtureQueryExecutionGraphQLServerTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-skip-dangerously-non-specific-scalar-type';
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getGraphQLServerModuleClassConfiguration(): array
    {
        return [
            ...parent::getGraphQLServerModuleClassConfiguration(),
            ...[
                \PoP\ComponentModel\Module::class => [
                    \PoP\ComponentModel\Environment::SKIP_EXPOSING_DANGEROUSLY_NON_SPECIFIC_SCALAR_TYPE_IN_SCHEMA => static::isEnabled(),
                ],
                \GraphQLByPoP\GraphQLServer\Module::class => [
                    \GraphQLByPoP\GraphQLServer\Environment::EXPOSE_GLOBAL_FIELDS_IN_GRAPHQL_SCHEMA => true,
                ],
            ]
        ];
    }
}
