<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class AccessPasswordProtectedPersistedQuerySuccessQueryExecutionFixtureWebserverRequestTest extends AbstractAccessPasswordProtectedPersistedQueryQueryExecutionFixtureWebserverRequestTestCase
{
    protected static function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-password-protected-persisted-queries-success';
    }

    protected static function viewSource(): bool
    {
        return false;
    }
}
