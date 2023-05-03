<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\Environment;

class AccessPendingPersistedQueryEndpointByEditorQueryExecutionFixtureWebserverRequestTest extends AbstractAccessPendingPersistedQueryEndpointQueryExecutionFixtureWebserverRequestTestCase
{
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-pending-persisted-query-endpoint-by-editor';
    }

    protected static function getLoginUsername(): string
    {
        return Environment::getIntegrationTestsAuthenticatedEditorUserUsername();
    }

    protected static function getLoginPassword(): string
    {
        return Environment::getIntegrationTestsAuthenticatedEditorUserPassword();
    }

    protected function getExpectedResponseStatusCode(): int
    {
        return 404;
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
        $providerItems['pending-persisted-query-endpoint-query'][0] = 'text/html';
        // expectedResponseBody. null => no exection of test
        $providerItems['pending-persisted-query-endpoint-query'][1] = null;
        return $providerItems;
    }
}
