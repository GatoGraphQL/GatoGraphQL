<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractSchemaConfigCustomizableConfigurationBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractSchemaConfigSchemaAllowAccessToEntriesBlock;
use PHPUnitForGraphQLAPI\GraphQLAPI\Integration\AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase;

class AllowedTaxonomyMetaOnSchemaConfigurationCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    use AllowedTaxonomyMetaFixtureEndpointWebserverRequestTestTrait;

    public const MOBILE_APP_SCHEMA_CONFIGURATION_ID = 193;

    protected function getEndpoint(): string
    {
        /**
         * This endpoint:
         *
         * - Has "Customize configuration? (Or use default from Settings?)" as Default (i.e. false)
         */
        return 'graphql/mobile-app/';
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCPTBlockAttributesNewValue(): array
    {
        return [
            AbstractSchemaConfigCustomizableConfigurationBlock::ATTRIBUTE_NAME_CUSTOMIZE_CONFIGURATION => 'true',
            BlockAttributeNames::ENTRIES => $this->getAllowedTaxonomyMetaKeyEntriesNewValue(),
        ];
    }

    protected function getCustomPostID(string $dataName): int
    {
        return self::MOBILE_APP_SCHEMA_CONFIGURATION_ID;
    }

    protected function getBlockNamespacedID(string $dataName): string
    {
        return 'graphql-api/schema-config-schema-taxonomy-meta';
    }
}
