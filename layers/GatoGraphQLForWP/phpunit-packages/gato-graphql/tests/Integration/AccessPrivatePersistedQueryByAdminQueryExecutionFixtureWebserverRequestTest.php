<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class AccessPrivatePersistedQueryByAdminQueryExecutionFixtureWebserverRequestTest extends AbstractAccessPrivatePersistedQueryQueryExecutionFixtureWebserverRequestTestCase
{
    protected static function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-private-persisted-queries-success';
    }

    protected static function viewSource(): bool
    {
        return false;
    }
}
