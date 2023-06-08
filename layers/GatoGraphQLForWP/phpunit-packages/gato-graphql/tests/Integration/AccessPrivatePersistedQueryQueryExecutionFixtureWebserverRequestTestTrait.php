<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

/**
 * Test that only the schema editor user can visualize/execute
 * a Private Persisted Query
 */
trait AccessPrivatePersistedQueryQueryExecutionFixtureWebserverRequestTestTrait
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-private-persisted-queries';
    }

    protected static function getEndpoint(): string
    {
        return sprintf(
            'graphql-query/comments-from-this-month/%s',
            static::viewSource()
                ? '?view=source'
                : ''
        );
    }

    abstract protected static function viewSource(): bool;
}
