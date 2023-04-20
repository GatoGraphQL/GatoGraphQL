<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractEndpointWebserverRequestTestCase;

trait AccessPrivatePersistedQueryFailsQueryExecutionFixtureWebserverRequestTestTrait
{
    protected function getResponseFixtureFolder(): string
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
    protected function customizeProviderEndpointEntries(array $providerItems): array
    {
        // expectedContentType
        $providerItems['private-persisted-query'][0] = 'text/html';
        return $providerItems;
    }

    /**
     * @see layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/src/Services/Helpers/RenderingHelpers.php
     */
    protected function getGraphQLExpectedResponse(): string
    {
        return '/You are not authorized to see this content/';
    }

    protected function getResponseComparisonType(): ?int
    {
        return AbstractEndpointWebserverRequestTestCase::RESPONSE_COMPARISON_REGEX;
    }
}
