<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigPayloadTypesForMutationsBlock;
use GraphQLByPoP\GraphQLServer\Configuration\MutationPayloadTypeOptions;
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
            SchemaConfigPayloadTypesForMutationsBlock::ATTRIBUTE_NAME_USE_PAYLOAD_TYPE => MutationPayloadTypeOptions::USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS,
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
