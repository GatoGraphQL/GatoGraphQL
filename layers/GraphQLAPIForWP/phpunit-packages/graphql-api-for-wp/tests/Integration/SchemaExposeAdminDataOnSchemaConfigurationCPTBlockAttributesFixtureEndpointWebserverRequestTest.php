<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeValues;

class SchemaExposeAdminDataOnSchemaConfigurationCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    public const WEBSITE_SCHEMA_CONFIGURATION_ID = 191;

    protected function getEndpoint(): string
    {
        /**
         * This endpoint:
         *
         * - Has "Expose admin elements in the schema?" as "Do not expose"
         * - Has the Schema Configuration "Website" (with ID 191)
         */
        return 'graphql/website/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-expose-admin-data-in-cpt';
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCPTBlockAttributesNewValue(): array
    {
        return [
            BlockAttributeNames::ENABLED_CONST => BlockAttributeValues::ENABLED,
        ];
    }

    protected function getCustomPostID(string|int $dataName): int
    {
        return self::WEBSITE_SCHEMA_CONFIGURATION_ID;
    }

    protected function getBlockNamespacedID(string|int $dataName): string
    {
        return 'graphql-api/schema-config-expose-admin-data';
    }
}
