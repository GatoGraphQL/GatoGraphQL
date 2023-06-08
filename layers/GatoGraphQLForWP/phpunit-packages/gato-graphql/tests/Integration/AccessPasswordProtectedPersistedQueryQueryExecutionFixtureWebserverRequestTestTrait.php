<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

/**
 * Test that only the schema editor user can visualize/execute
 * a Password-Protected Persisted Query
 */
trait AccessPasswordProtectedPersistedQueryQueryExecutionFixtureWebserverRequestTestTrait
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-password-protected-persisted-queries';
    }

    protected static function getEndpoint(): string
    {
        return sprintf(
            'graphql-query/password-protected-persisted-query/%s',
            static::viewSource()
                ? '?view=source'
                : ''
        );
    }

    abstract protected static function viewSource(): bool;
}
