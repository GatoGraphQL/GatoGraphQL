<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use PoP\Root\Module\ModuleInterface;

abstract class AbstractExposeGlobalFieldsInRootTypeOnlyInGraphQLSchemaWithNestedMutationsFixtureQueryExecutionGraphQLServerTest extends AbstractExposeGlobalFieldsInRootTypeOnlyInGraphQLSchemaFixtureQueryExecutionGraphQLServerTest
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-expose-global-fields-in-root-type-only-in-graphql-schema-with-nested-mutations';
    }

    /**
     * @return array<class-string<ModuleInterface>,array<string,mixed>>
     */
    protected static function getGraphQLServerModuleClassConfiguration(): array
    {
        $graphQLServerModuleClassConfiguration = parent::getGraphQLServerModuleClassConfiguration();
        $graphQLServerModuleClassConfiguration[\GraphQLByPoP\GraphQLServer\Module::class][\GraphQLByPoP\GraphQLServer\Environment::ENABLE_NESTED_MUTATIONS] = true;
        return $graphQLServerModuleClassConfiguration;
    }
}
