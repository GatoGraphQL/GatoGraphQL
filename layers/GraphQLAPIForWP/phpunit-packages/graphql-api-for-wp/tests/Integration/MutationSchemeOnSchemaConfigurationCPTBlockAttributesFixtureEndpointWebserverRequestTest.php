<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigMutationSchemeBlock;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;

class MutationSchemeOnSchemaConfigurationCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    public const WEBSITE_SCHEMA_CONFIGURATION_ID = 191;

    protected function getEndpoint(): string
    {
        /**
         * This endpoint:
         *
         * - Has "Support nested mutations?" as "Default"
         * - Has the Schema Configuration "Website" (with ID 191)
         */
        return 'graphql/website/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-mutation-scheme-in-cpt';
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCPTBlockAttributesNewValue(): array
    {
        return [
            SchemaConfigMutationSchemeBlock::ATTRIBUTE_NAME_MUTATION_SCHEME => MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS,
        ];
    }

    protected function getCustomPostID(string $dataName): int
    {
        return self::WEBSITE_SCHEMA_CONFIGURATION_ID;
    }

    protected function getBlockNamespacedID(string $dataName): string
    {
        return 'graphql-api/schema-config-mutation-scheme';
    }
}
