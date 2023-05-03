<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class SchemaTypeFixtureEndpointWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCaseCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-type';
    }

    /**
     * Single endpoint
     */
    protected function getEndpoint(): string
    {
        return 'graphql';
    }
}
