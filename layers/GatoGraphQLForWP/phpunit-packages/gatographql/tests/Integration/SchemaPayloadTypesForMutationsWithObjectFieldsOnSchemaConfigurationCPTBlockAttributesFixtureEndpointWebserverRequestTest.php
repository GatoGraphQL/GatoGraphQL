<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;
use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeValues;
use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase;

class SchemaPayloadTypesForMutationsWithObjectFieldsOnSchemaConfigurationCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    public const MOBILE_APP_SCHEMA_CONFIGURATION_ID = 193;

    protected static function getEndpoint(): string
    {
        /**
         * This endpoint:
         *
         * - Has "Use Payload Types for Mutations" as Default (i.e. true)
         * - Has the Schema Configuration "Mobile App" (with ID 193)
         */
        return 'graphql/mobile-app/';
    }

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-payload-types-for-mutations-with-object-fields';
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCPTBlockAttributesNewValue(): array
    {
        return [
            BlockAttributeNames::ENABLED_CONST => BlockAttributeValues::DISABLED,
        ];
    }

    protected function getCustomPostID(string $dataName): int
    {
        return self::MOBILE_APP_SCHEMA_CONFIGURATION_ID;
    }

    protected function getBlockNamespacedID(string $dataName): string
    {
        return 'gatographql/schema-config-payload-types-for-mutations';
    }
}
