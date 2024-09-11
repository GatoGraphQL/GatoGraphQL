<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginDataSetup;

use GatoGraphQL\GatoGraphQL\Constants\PluginSetupDataEntrySlugs;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigMutationSchemeBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigPayloadTypesForMutationsBlock;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy;
use GatoGraphQL\GatoGraphQL\StaticHelpers\BlockUtils;
use GatoGraphQL\GatoGraphQL\StaticHelpers\PluginSetupDataHelpers;
use GraphQLByPoP\GraphQLServer\Configuration\MutationPayloadTypeOptions;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;
use WP_Error;

class PluginDataSetupService implements PluginDataSetupServiceInterface
{
    use StandaloneServiceTrait;

    public function getNestedMutationsSchemaConfigurationCustomPostID(): ?int
    {
        $slug = PluginSetupDataEntrySlugs::SCHEMA_CONFIGURATION_NESTED_MUTATIONS;
        $schemaConfigurationID = PluginSetupDataHelpers::getSchemaConfigurationID($slug);
        if ($schemaConfigurationID !== null) {
            return $schemaConfigurationID;
        }

        $nestedMutationsBlockDataItem = $this->getNestedMutationsBlockDataItem();
        return $this->createSchemaConfigurationID(
            $slug,
            \__('Nested mutations', 'gatographql'),
            [
                $nestedMutationsBlockDataItem,
            ]
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function getNestedMutationsBlockDataItem(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var SchemaConfigMutationSchemeBlock */
        $schemaConfigMutationSchemeBlock = $instanceManager->getInstance(SchemaConfigMutationSchemeBlock::class);

        return [
            'blockName' => $schemaConfigMutationSchemeBlock->getBlockFullName(),
            'attrs' => [
                SchemaConfigMutationSchemeBlock::ATTRIBUTE_NAME_MUTATION_SCHEME => MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS,
            ]
        ];
    }

    /**
     * @param array<array<string,mixed>> $blockDataItems
     */
    public function createSchemaConfigurationID(string $slug, string $title, array $blockDataItems): ?int
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLSchemaConfigurationCustomPostType */
        $graphQLSchemaConfigurationCustomPostType = $instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);

        $schemaConfigurationCustomPostID = wp_insert_post([
            'post_status' => 'publish',
            'post_name' => $slug,
            'post_type' => $graphQLSchemaConfigurationCustomPostType->getCustomPostType(),
            'post_title' => $title,
            'post_content' => serialize_blocks(BlockUtils::addInnerContentToBlockAttrs($blockDataItems))
        ]);
        if ($schemaConfigurationCustomPostID === 0) {
            return null;
        }
        return $schemaConfigurationCustomPostID;
    }

    public function getBulkMutationsSchemaConfigurationCustomPostID(): ?int
    {

        $slug = PluginSetupDataEntrySlugs::SCHEMA_CONFIGURATION_BULK_MUTATIONS;
        $schemaConfigurationID = PluginSetupDataHelpers::getSchemaConfigurationID($slug);
        if ($schemaConfigurationID !== null) {
            return $schemaConfigurationID;
        }

        $nestedMutationsBlockDataItem = $this->getNestedMutationsBlockDataItem();
        $useAndQueryPayloadTypeForMutationsBlockDataItem = $this->getUseAndQueryPayloadTypeForMutationseBlockDataItem();
        return $this->createSchemaConfigurationID(
            $slug,
            \__('Bulk mutations', 'gatographql'),
            [
                $nestedMutationsBlockDataItem,
                $useAndQueryPayloadTypeForMutationsBlockDataItem,
            ]
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function getUseAndQueryPayloadTypeForMutationseBlockDataItem(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var SchemaConfigPayloadTypesForMutationsBlock */
        $schemaConfigPayloadTypesForMutationsBlock = $instanceManager->getInstance(SchemaConfigPayloadTypesForMutationsBlock::class);

        return [
            'blockName' => $schemaConfigPayloadTypesForMutationsBlock->getBlockFullName(),
            'attrs' => [
                SchemaConfigPayloadTypesForMutationsBlock::ATTRIBUTE_NAME_USE_PAYLOAD_TYPE => MutationPayloadTypeOptions::USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS,
            ]
        ];
    }

    public function getAdminEndpointCategoryID(): ?int
    {
        $slug = 'admin';
        $endpointCategoryID = PluginSetupDataHelpers::getEndpointCategoryID($slug);
        if ($endpointCategoryID !== null) {
            return $endpointCategoryID;
        }

        return $this->createEndpointCategoryID(
            $slug,
            \__('Admin', 'gatographql'),
            \__('Internal admin tasks', 'gatographql'),
        );
    }

    public function createEndpointCategoryID(string $slug, string $name, string $description): ?int
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLEndpointCategoryTaxonomy */
        $graphQLEndpointCategoryTaxonomy = $instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);

        $endpointCategoryTerm = \wp_insert_term(
            $name,
            $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
            [
                'slug' => $slug,
                $description
            ]
        );
        if ($endpointCategoryTerm instanceof WP_Error) {
            return null;
        }
        return $endpointCategoryTerm['term_id'];
    }

    public function getWebhookEndpointCategoryID(): ?int
    {
        $slug = 'webhook';
        $endpointCategoryID = PluginSetupDataHelpers::getEndpointCategoryID($slug);
        if ($endpointCategoryID !== null) {
            return $endpointCategoryID;
        }

        return $this->createEndpointCategoryID(
            $slug,
            \__('Webhook', 'gatographql'),
            \__('Process data from external services', 'gatographql'),
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function getAdminEndpointTaxInputData(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var GraphQLEndpointCategoryTaxonomy */
        $graphQLEndpointCategoryTaxonomy = $instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);

        $endpointCategoryTaxonomy = $graphQLEndpointCategoryTaxonomy->getTaxonomy();

        $adminEndpointTaxInputData = [
            $endpointCategoryTaxonomy => [],
        ];
        $adminEndpointCategoryID = $this->getAdminEndpointCategoryID();
        if ($adminEndpointCategoryID !== null) {
            $adminEndpointTaxInputData[$endpointCategoryTaxonomy][] = $adminEndpointCategoryID;
        }

        return $adminEndpointTaxInputData;
    }

    /**
     * @return array<string,mixed>
     */
    public function getWebhookEndpointTaxInputData(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var GraphQLEndpointCategoryTaxonomy */
        $graphQLEndpointCategoryTaxonomy = $instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);

        $endpointCategoryTaxonomy = $graphQLEndpointCategoryTaxonomy->getTaxonomy();

        $webhookEndpointTaxInputData = [
            $endpointCategoryTaxonomy => [],
        ];
        $webhookEndpointCategoryID = $this->getWebhookEndpointCategoryID();
        if ($webhookEndpointCategoryID !== null) {
            $webhookEndpointTaxInputData[$endpointCategoryTaxonomy][] = $webhookEndpointCategoryID;
        }

        return $webhookEndpointTaxInputData;
    }
}
