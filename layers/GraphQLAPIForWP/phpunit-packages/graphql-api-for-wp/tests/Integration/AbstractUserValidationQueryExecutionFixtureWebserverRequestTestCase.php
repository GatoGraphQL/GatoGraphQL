<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

abstract class AbstractUserValidationQueryExecutionFixtureWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    /**
     * "Power users" custom endpoint, to be tested
     * both with/out user authenticated
     */
    protected function getEndpoint(): string
    {
        return 'graphql/power-users/';
    }
}
