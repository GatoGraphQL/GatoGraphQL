<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class DisableSchemaModulesOnPrivateEndpointTestOnSingleEndpointsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return 'graphql/';
    }

    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints-has-change';
    }
}
