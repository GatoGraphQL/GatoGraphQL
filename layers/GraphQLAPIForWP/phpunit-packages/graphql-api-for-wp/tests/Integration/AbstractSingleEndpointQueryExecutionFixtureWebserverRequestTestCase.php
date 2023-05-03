<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

abstract class AbstractSingleEndpointQueryExecutionFixtureWebserverRequestTestCaseCase extends AbstractFixtureEndpointWebserverRequestTestCaseCase
{
    /**
     * Single endpoint, with non-logged-in user
     */
    protected function getEndpoint(): string
    {
        return 'graphql';
    }
}
