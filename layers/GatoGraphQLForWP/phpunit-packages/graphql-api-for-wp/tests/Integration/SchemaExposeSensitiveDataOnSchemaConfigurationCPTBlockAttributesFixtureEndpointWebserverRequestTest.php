<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;
use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeValues;

class SchemaExposeSensitiveDataOnSchemaConfigurationCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    public const WEBSITE_SCHEMA_CONFIGURATION_ID = 191;

    protected function getEndpoint(): string
    {
        /**
         * This endpoint:
         *
         * - Has "Expose sensitive data in the schema" as "Do not expose"
         * - Has the Schema Configuration "Website" (with ID 191)
         */
        return 'graphql/website/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-expose-sensitive-data-in-cpt';
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

    protected function getCustomPostID(string $dataName): int
    {
        return self::WEBSITE_SCHEMA_CONFIGURATION_ID;
    }

    protected function getBlockNamespacedID(string $dataName): string
    {
        return 'gato-graphql/schema-config-expose-sensitive-data';
    }
}
