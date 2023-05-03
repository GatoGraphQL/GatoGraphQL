<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeValues;
use PHPUnitForGraphQLAPI\GraphQLAPI\Integration\AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCaseCase;

class SchemaPayloadTypesForMutationsOnSchemaConfigurationCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCaseCase
{
    public const MOBILE_APP_SCHEMA_CONFIGURATION_ID = 193;

    protected function getEndpoint(): string
    {
        /**
         * This endpoint:
         *
         * - Has "Use Payload Types for Mutations" as Default (i.e. true)
         * - Has the Schema Configuration "Mobile App" (with ID 193)
         */
        return 'graphql/mobile-app/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-payload-types-for-mutations';
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
        return 'graphql-api/schema-config-payload-types-for-mutations';
    }
}
