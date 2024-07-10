<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;

class CustomEndpointOptionsOnCustomEndpointCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    public const WEBSITE_CUSTOM_ENDPOINT_ID = 196;

    protected static function getEndpoint(): string
    {
        return 'graphql/website/';
    }

    protected static function getFixtureFolder(): string
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

    protected function getCustomPostID(string $dataName): int
    {
        return self::WEBSITE_CUSTOM_ENDPOINT_ID;
    }

    protected function getBlockNamespacedID(string $dataName): string
    {
        return 'gatographql/custom-endpoint-options';
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
        $providerItems['query'][0] = 'text/html';
        // expectedResponseBody. null => no execution of test
        $providerItems['query'][1] = null;
        return $providerItems;
    }
}
