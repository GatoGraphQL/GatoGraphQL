<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class DefaultSchemaConfigurationForSingleEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDefaultSchemaConfigurationForEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest
{
    /**
     * Single endpoint
     */
    protected function getEndpoint(): string
    {
        return 'graphql';
    }

    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-schema-configuration-for-single-and-custom-endpoints';
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_single-endpoint';
    }
}
