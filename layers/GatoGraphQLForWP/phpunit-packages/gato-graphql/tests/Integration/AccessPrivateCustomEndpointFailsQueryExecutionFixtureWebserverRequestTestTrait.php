<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

trait AccessPrivateCustomEndpointFailsQueryExecutionFixtureWebserverRequestTestTrait
{
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-private-custom-endpoints-failure';
    }

    /**
     * This test disables the endpoint, then update the providerItem
     * via code.
     *
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected function customizeProviderEndpointEntries(array $providerItems): array
    {
        // expectedContentType
        $providerItems['private-custom-endpoint'][0] = 'text/html';
        $providerItems['private-custom-endpoint'][1] = null;
        return $providerItems;
    }

    protected function getExpectedResponseStatusCode(): int
    {
        return 404;
    }
}
