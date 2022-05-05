<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

abstract class AbstractSingleEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    /**
     * Single endpoint
     */
    protected function getEndpoint(): string
    {
        return 'graphql';
    }
}
