<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class AccessPrivateCustomEndpointClientByNonLoggedInUserQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use AccessPrivateCustomEndpointQueryExecutionFixtureWebserverRequestTestTrait;
    use AccessPrivateCustomEndpointFailsQueryExecutionFixtureWebserverRequestTestTrait;

    protected static function accessClient(): bool
    {
        return true;
    }
}
