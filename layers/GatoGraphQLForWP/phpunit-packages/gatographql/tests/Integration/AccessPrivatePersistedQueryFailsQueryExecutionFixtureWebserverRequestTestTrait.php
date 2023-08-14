<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

trait AccessPrivatePersistedQueryFailsQueryExecutionFixtureWebserverRequestTestTrait
{
    protected static function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-private-persisted-queries-failure';
    }

    /**
     * This test disables the endpoint, then update the providerItem
     * via code.
     *
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected static function customizeProviderEndpointEntries(array $providerItems): array
    {
        // expectedContentType
        $providerItems['private-persisted-query'][0] = 'text/html';
        $providerItems['private-persisted-query'][1] = null;
        return $providerItems;
    }

    protected function getExpectedResponseStatusCode(): int
    {
        return 404;
    }
}
