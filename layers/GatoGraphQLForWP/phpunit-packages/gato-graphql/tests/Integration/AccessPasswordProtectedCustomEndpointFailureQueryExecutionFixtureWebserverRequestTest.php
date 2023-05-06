<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class AccessPasswordProtectedCustomEndpointFailureQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use AccessPasswordProtectedCustomEndpointQueryExecutionFixtureWebserverRequestTestTrait;
    use AccessPasswordProtectedCustomEndpointFailsQueryExecutionFixtureWebserverRequestTestTrait;

    protected function accessClient(): bool
    {
        return false;
    }
}
