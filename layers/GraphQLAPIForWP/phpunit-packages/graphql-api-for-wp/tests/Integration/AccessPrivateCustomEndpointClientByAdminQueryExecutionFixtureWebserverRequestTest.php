<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class AccessPrivateCustomEndpointClientByAdminQueryExecutionFixtureWebserverRequestTest extends AbstractAccessPrivateCustomEndpointQueryExecutionFixtureWebserverRequestTest
{
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-private-custom-endpoints-success';
    }

    protected function accessClient(): bool
    {
        return true;
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
        /**
         * Expect to NOT find the "You are not authorized" message
         *
         * @see layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/src/Services/Helpers/RenderingHelpers.php
         */
        $providerItems['private-custom-endpoint'][1] = '/(?<!You are not authorized to see this content)/';
        return $providerItems;
    }

    protected function getResponseComparisonType(): ?int
    {
        return self::RESPONSE_COMPARISON_REGEX;
    }
}
