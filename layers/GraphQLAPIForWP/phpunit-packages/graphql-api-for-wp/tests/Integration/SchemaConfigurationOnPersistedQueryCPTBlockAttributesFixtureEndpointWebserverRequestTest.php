<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Services\Blocks\EndpointSchemaConfigurationBlock;

class SchemaConfigurationOnPersistedQueryCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    public const LATEST_POSTS_FOR_MOBILE_APP_PERSISTED_QUERY_ID = 65;
    public const POWER_USERS_SCHEMA_CONFIGURATION_ID = 261;

    protected function getEndpoint(): string
    {
        /**
         * This endpoint:
         *
         * - Originally has the Schema Configuration "Mobile App"
         * - Then changed to "Power users"
         */
        return 'graphql-query/latest-posts-for-mobile-app/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-config-on-persisted-query-in-cpt';
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCPTBlockAttributesNewValue(): array
    {
        return [
            EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION => self::POWER_USERS_SCHEMA_CONFIGURATION_ID,
        ];
    }

    protected function getCustomPostID(string|int $dataName): int
    {
        return self::LATEST_POSTS_FOR_MOBILE_APP_PERSISTED_QUERY_ID;
    }

    protected function getBlockNamespacedID(string|int $dataName): string
    {
        return 'graphql-api/schema-configuration';
    }
}
