<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\Environment;

class AccessPrivatePersistedQueryByEditorQueryExecutionFixtureWebserverRequestTest extends AbstractAccessPrivatePersistedQueryQueryExecutionFixtureWebserverRequestTest
{
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-private-persisted-queries-by-editor';
    }

    protected static function getLoginUsername(): string
    {
        return Environment::getIntegrationTestsAuthenticatedEditorUserUsername();
    }

    protected static function getLoginPassword(): string
    {
        return Environment::getIntegrationTestsAuthenticatedEditorUserPassword();
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
        $providerItems['private-persisted-query'][0] = 'text/html';
        return $providerItems;
    }

    protected function getGraphQLExpectedResponse(): string
    {
        return '/You are not authorized to see this content/';
    }

    protected function getResponseComparisonType(): ?int
    {
        return self::RESPONSE_COMPARISON_REGEX;
    }
}
