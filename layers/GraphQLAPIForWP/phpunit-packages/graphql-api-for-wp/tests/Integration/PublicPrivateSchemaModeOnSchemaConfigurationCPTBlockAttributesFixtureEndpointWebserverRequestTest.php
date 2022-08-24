<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaModeBlock;
use PoP\AccessControl\Schema\SchemaModes;

class PublicPrivateSchemaModeOnSchemaConfigurationCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    public const POWER_USERS_SCHEMA_CONFIGURATION_ID = 261;

    protected function getEndpoint(): string
    {
        /**
         * This endpoint:
         *
         * - Has "Visibility of the schema metadata:" as "Default" (then it's "public")
         * - Has the Schema Configuration "Power users" (with ID 261)
         */
        return 'graphql/power-users/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-public-private-schema-mode-in-cpt';
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCPTBlockAttributesNewValue(): array
    {
        return [
            SchemaConfigSchemaModeBlock::ATTRIBUTE_NAME_DEFAULT_SCHEMA_MODE => SchemaModes::PRIVATE_SCHEMA_MODE,
        ];
    }

    protected function getCustomPostID(string $dataName): int
    {
        return self::POWER_USERS_SCHEMA_CONFIGURATION_ID;
    }

    protected function getBlockNamespacedID(string $dataName): string
    {
        return 'graphql-api/schema-config-schema-mode';
    }
}
