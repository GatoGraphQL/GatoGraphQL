<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigurationBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use WP_Post;

abstract class AbstractQueryExecutionSchemaConfigurator implements SchemaConfiguratorInterface
{
    public function __construct(
        protected InstanceManagerInterface $instanceManager,
        protected ModuleRegistryInterface $moduleRegistry,
    ) {
    }

    /**
     * Extract the items defined in the Schema Configuration,
     * and inject them into the service as to take effect in the current GraphQL query
     */
    public function executeSchemaConfiguration(int $customPostID): void
    {
        // Check if it enabled by module
        if (!$this->moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION)) {
            return;
        }

        if ($schemaConfigurationID = $this->getSchemaConfigurationID($customPostID)) {
            // Get that Schema Configuration, and load its settings
            $this->executeSchemaConfigurationItems($schemaConfigurationID);
        }
    }

    /**
     * Return the stored Schema Configuration ID
     */
    protected function getUserSettingSchemaConfigurationID(): ?int
    {
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $schemaConfigurationID = $userSettingsManager->getSetting(
            SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
            SchemaConfigurationFunctionalityModuleResolver::OPTION_SCHEMA_CONFIGURATION_ID
        );
        // `null` is stored as OPTION_VALUE_NO_VALUE_ID
        if ($schemaConfigurationID == SchemaConfigurationFunctionalityModuleResolver::OPTION_VALUE_NO_VALUE_ID) {
            return null;
        }
        return $schemaConfigurationID;
    }

    /**
     * Extract the Schema Configuration ID from the block stored in the post
     */
    protected function getSchemaConfigurationID(int $customPostID): ?int
    {
        /** @var BlockHelpers */
        $blockHelpers = $this->instanceManager->getInstance(BlockHelpers::class);
        /**
         * @var SchemaConfigurationBlock
         */
        $block = $this->instanceManager->getInstance(SchemaConfigurationBlock::class);
        $schemaConfigurationBlockDataItem = $blockHelpers->getSingleBlockOfTypeFromCustomPost(
            $customPostID,
            $block
        );
        // If there was no schema configuration, then the default one has been selected
        // It is not saved in the DB, because it has been set as the default value in
        // blocks/schema-configuration/src/index.js
        if (is_null($schemaConfigurationBlockDataItem)) {
            return $this->getUserSettingSchemaConfigurationID();
        }

        $schemaConfiguration = $schemaConfigurationBlockDataItem['attrs'][SchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION] ?? null;
        // Check if $schemaConfiguration is one of the meta options (default, none, inherit)
        if ($schemaConfiguration == SchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE) {
            return null;
        } elseif ($schemaConfiguration == SchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT) {
            return $this->getUserSettingSchemaConfigurationID();
        } elseif ($schemaConfiguration == SchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT) {
            // If disabled by module, then return nothing
            if (!$this->moduleRegistry->isModuleEnabled(EndpointFunctionalityModuleResolver::API_HIERARCHY)) {
                return null;
            }
            // Return the schema configuration from the parent, or null if no parent exists
            /**
             * @var WP_Post|null
             */
            $customPost = \get_post($customPostID);
            if (!is_null($customPost) && $customPost->post_parent) {
                return $this->getSchemaConfigurationID($customPost->post_parent);
            }
            return null;
        }
        // It is already the ID, or null if blocks returned empty
        // (eg: because parent post was trashed)
        return $schemaConfiguration;
    }

    abstract protected function executeSchemaConfigurationItems(int $schemaConfigurationID): void;
}
