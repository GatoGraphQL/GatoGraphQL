<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class ErrorFixtureEndpointWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-errors';
    }
    
    /**
     * Single endpoint
     */
    protected function getEndpoint(): string
    {
        return 'graphql';
    }
}
