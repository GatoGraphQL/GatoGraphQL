<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

abstract class AbstractSingleEndpointQueryExecutionFixtureWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    /**
     * Single endpoint, with non-logged-in user
     */
    protected function getEndpoint(): string
    {
        return 'graphql';
    }
}
