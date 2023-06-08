<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class AccessPasswordProtectedCustomEndpointClientFailureQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use AccessPasswordProtectedCustomEndpointQueryExecutionFixtureWebserverRequestTestTrait;
    use AccessPasswordProtectedCustomEndpointFailsQueryExecutionFixtureWebserverRequestTestTrait;

    protected static function accessClient(): bool
    {
        return true;
    }
}
