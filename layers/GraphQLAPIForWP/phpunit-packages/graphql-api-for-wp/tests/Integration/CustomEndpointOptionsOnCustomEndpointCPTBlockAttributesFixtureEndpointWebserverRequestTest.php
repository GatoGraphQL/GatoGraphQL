<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;

class CustomEndpointOptionsOnCustomEndpointCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    public const WEBSITE_CUSTOM_ENDPOINT_ID = 196;

    protected function getEndpoint(): string
    {
        return 'graphql/website/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-custom-endpoint-options-on-custom-endpoint-in-cpt';
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCPTBlockAttributesNewValue(): array
    {
        return [
            BlockAttributeNames::IS_ENABLED => false,
        ];
    }

    protected function getCustomPostID(string|int $dataName): int
    {
        return self::WEBSITE_CUSTOM_ENDPOINT_ID;
    }

    protected function getBlockNamespacedID(string|int $dataName): string
    {
        return 'graphql-api/custom-endpoint-options';
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
        $providerItems['query'][0] = 'text/html';
        // expectedResponseBody. null => no exection of test
        $providerItems['query'][1] = null;
        return $providerItems;
    }
}
