<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class AccessPasswordProtectedPersistedQueryFailureQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use AccessPasswordProtectedPersistedQueryQueryExecutionFixtureWebserverRequestTestTrait;
    use AccessPasswordProtectedPersistedQueryFailsQueryExecutionFixtureWebserverRequestTestTrait;

    protected static function viewSource(): bool
    {
        return false;
    }
}
