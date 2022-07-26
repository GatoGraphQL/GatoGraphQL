<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigAccessControlListBlock;

class AccessControlListsOnSchemaConfigurationCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    public const WEBSITE_SCHEMA_CONFIGURATION_ID = 191;
    public const POWER_USERS_ACL_ID = 256;

    protected function getEndpoint(): string
    {
        /**
         * This endpoint:
         *
         * - Has the Schema Configuration "Website" (with ID 191)
         */
        return 'graphql/website/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-acls-in-cpt';
    }

    protected function getCPTBlockAttributesNewValue(): array
    {
        return [
            SchemaConfigAccessControlListBlock::ATTRIBUTE_NAME_ACCESS_CONTROL_LISTS => [
                self::POWER_USERS_ACL_ID,
            ]
        ];
    }

    protected function getCustomPostID(string $dataName): int
    {
        return self::WEBSITE_SCHEMA_CONFIGURATION_ID;
    }

    protected function getBlockNamespacedID(string $dataName): string
    {
        return 'graphql-api/schema-config-access-control-lists';
    }
}
