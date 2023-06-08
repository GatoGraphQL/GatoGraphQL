<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Services\Blocks\EndpointSchemaConfigurationBlock;

class SchemaConfigurationOnPersistedQueryCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    public const HOME_USER_WIDGET_PERSISTED_QUERY_ID = 12;
    public const UNRESTRICTED_SCHEMA_SCHEMA_CONFIGURATION_ID = 304;

    protected static function getEndpoint(): string
    {
        return 'graphql-query/website/home-user-widget/';
    }

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-config-on-persisted-query-in-cpt';
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
        return self::HOME_USER_WIDGET_PERSISTED_QUERY_ID;
    }

    protected function getBlockNamespacedID(string $dataName): string
    {
        return 'gato-graphql/schema-configuration';
    }
}
