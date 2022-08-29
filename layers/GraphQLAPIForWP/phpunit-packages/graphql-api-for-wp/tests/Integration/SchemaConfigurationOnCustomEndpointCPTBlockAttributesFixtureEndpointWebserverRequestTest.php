<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Services\Blocks\EndpointSchemaConfigurationBlock;

class SchemaConfigurationOnCustomEndpointCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    public const WEBSITE_CUSTOM_ENDPOINT_ID = 196;
    public const UNRESTRICTED_SCHEMA_SCHEMA_CONFIGURATION_ID = 304;

    protected function getEndpoint(): string
    {
        /**
         * This endpoint:
         *
         * - Originally has the Schema Configuration "Website" (with ID 191)
         * - Then changed to "Power users" (with ID 261)
         */
        return 'graphql/website/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-config-on-custom-endpoint-in-cpt';
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCPTBlockAttributesNewValue(): array
    {
        return [
            EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION => self::UNRESTRICTED_SCHEMA_SCHEMA_CONFIGURATION_ID,
        ];
    }

    protected function getCustomPostID(string $dataName): int
    {
        return self::WEBSITE_CUSTOM_ENDPOINT_ID;
    }

    protected function getBlockNamespacedID(string $dataName): string
    {
        return 'graphql-api/schema-configuration';
    }
}
