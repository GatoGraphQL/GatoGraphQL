<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class AccessPasswordProtectedPersistedQuerySuccessQueryExecutionFixtureWebserverRequestTest extends AbstractAccessPasswordProtectedPersistedQueryQueryExecutionFixtureWebserverRequestTestCase
{
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-password-protected-persisted-queries-success';
    }

    protected function viewSource(): bool
    {
        return false;
    }
}
